jQuery.geocode = function(geobyte_sel, maxmind_sel, browserinfo_sel, country_sel) {
	g = {};
	m = {};
	b = {};
	if ((geobyte_info = $.cookie('geobyte_info')) == null) {
		$.ajax({
			type: 'GET',
			url: 'http://gd.geobytes.com/gd?after=-1&variables=sGeobytesIso2,sGeobytesRegion,sGeobytesCity,sGeobytesCertainty,sGeobytesLatitude,sGeobytesLongitude,sGeobytesTimezone,sGeobytesIsProxyNetwork',
			dataType: 'script',
			cache: true,
			success: function() {
				g.country_code = sGeobytesIso2;
				g.region_name = sGeobytesRegion;
				g.city = sGeobytesCity;
				g.certainty = sGeobytesCertainty;
				g.latitude = sGeobytesLatitude;
				g.longitude = sGeobytesLongitude;
				g.timezone = sGeobytesTimezone;
				g.proxy = sGeobytesIsProxyNetwork;
				$.ajax({
					type: 'GET',
					url: 'http://ws.geonames.org/timezoneJSON?lat=' + g.latitude + '&lng=' + g.longitude + '&username=agriya&callback=?',
					dataType: 'json',
					cache: true,
					success: function(timezone) {
						g.gn_countryCode = timezone.countryCode;
						g.gn_countryName = timezone.countryName;
						g.gn_lat = timezone.lat;
						g.gn_lng = timezone.lng;
						g.gn_timezoneId = timezone.timezoneId;
						g.gn_dstOffset = timezone.dstOffset;
						g.gn_gmtOffset = timezone.gmtOffset;
						g.gn_rawOffset = timezone.rawOffset;
						g_str = JSON.stringify(g);
						$.cookie('geobyte_info', g_str, {
							expires: 100
						});
						$(geobyte_sel).val(g_str);
					}
				});
				g_str = JSON.stringify(g);
				$.cookie('geobyte_info', g_str, {
					expires: 100
				});
				$(geobyte_sel).val(g_str);
			}
		});
	}
	else {
		$(geobyte_sel).val(geobyte_info);
	}
	if ((maxmind_info = $.cookie('maxmind_info')) == null) {
		$.ajax({
			type: 'GET',
			url: 'http://j.maxmind.com/app/geoip.js',
			dataType: 'script',
			cache: true,
			success: function() {
				country_code = m.country_code = geoip_country_code();
				m.city = geoip_city();
				m.region_name = geoip_region_name();
				m.latitude = geoip_latitude();
				m.longitude = geoip_longitude();
				m.postal_code = geoip_postal_code();
				if (country_code != '' && $(country_sel).val() == '') {
        			$.ajax({
        				type: 'GET',
        				url: __cfg('path_absolute') + 'countries/check_country/' + country_code,
        				dataType: 'script',
        				cache: true,
        				success: function(responsedata) {
        					if (responsedata != '') {
        						$(country_sel).val(responsedata);
        					}
        				}
        			});
        		}
				$.ajax({
					type: 'GET',
					url: 'http://ws.geonames.org/timezoneJSON?lat=' + m.latitude + '&lng=' + m.longitude + '&username=agriya&callback=?',
					dataType: 'json',
					cache: true,
					success: function(timezone) {
						m.mx_countryCode = timezone.countryCode;
						$.cookie('country_code', m.mx_countryCode, {
        					expires: 100
		          		});
						m.mx_countryName = timezone.countryName;
						m.mx_lat = timezone.lat;
						m.mx_lng = timezone.lng;
						m.mx_timezoneId = timezone.timezoneId;
						m.mx_dstOffset = timezone.dstOffset;
						m.mx_gmtOffset = timezone.gmtOffset;
						m.mx_rawOffset = timezone.rawOffset;
						m_str = JSON.stringify(m);
						$.cookie('maxmind_info', m_str, {
							expires: 100
						});
						$(maxmind_sel).val(m_str);
					}
				});
				m_str = JSON.stringify(m);
				$.cookie('maxmind_info', m_str, {
					expires: 100
				});
				$(maxmind_sel).val(m_str);
			}
		});
	}
	else {
		$(maxmind_sel).val(maxmind_info);
	}
	if ((browser_info = $.cookie('browser_info')) == null) {
		if (navigator.userLanguage) {
			b.browser_lang = navigator.userLanguage;
		}
		else if (navigator.language) {
			b.browser_lang = navigator.language;
		}
		else {
			b.browser_lang = 'en_US';
		}
		b.browser_timezone = -new Date().getTimezoneOffset() / 60;
		b.resolution = screen.width + 'x' + screen.height + 'x' + screen.colorDepth;
		b.browser = BrowserDetect.browser;
		b.os = BrowserDetect.OS;
		b.useragent = navigator.userAgent;
		// check dst for timezone
		var rightNow = new Date();
		var jan1 = new Date(rightNow.getFullYear(), 0, 1, 0, 0, 0, 0);
		var temp = jan1.toGMTString();
		var jan2 = new Date(temp.substring(0, temp.lastIndexOf(" ")-1));
		var std_time_offset = (jan1 - jan2) / (1000 * 60 * 60);
		var june1 = new Date(rightNow.getFullYear(), 6,  1, 0, 0, 0, 0);
		temp = june1.toGMTString();
		var june2 = new Date(temp.substring(0, temp.lastIndexOf(" ")-1));
		var daylight_time_offset = (june1 - june2) / (1000 * 60 * 60);
		var dst;
		b.gmt_offset = std_time_offset;
		b.dst_offset = daylight_time_offset;
		if (std_time_offset == daylight_time_offset) {
			b.dst = '0';
		} else {
			b.dst = '1';
		}
		b_str = JSON.stringify(b);
		$.cookie('browser_info', b_str, {
			expires: 100
		});
		$(browserinfo_sel).val(b_str);
	}
	else {
		$(browserinfo_sel).val(browser_info);
	}
	if ($(country_sel) && $(country_sel).val() == '') {
        country_code = $.cookie('country_code');
		if (country_code != '' && country_code != null && $(country_sel).val() == '') {
			$.ajax({
				type: 'GET',
				url: __cfg('path_absolute') + 'countries/check_country/' + country_code,
				dataType: 'script',
				cache: true,
				success: function(responsedata) { 
					if (responsedata != '') {
						$(country_sel).val(responsedata);
					}
				}
			});
		}
	}
};