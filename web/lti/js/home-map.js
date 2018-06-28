jQuery(function($) {

	var $win = $(window),
		$mapContainer = $('#home-map'),
		$mapBlock = $('#home-map-ui'),
		mapBlock = $mapBlock[0];
		
		
	// check it's height


	function checkScroll() {
		var winScroll = $win.scrollTop(),
			winHeight = $win.height(),
			mapTop = $mapContainer.offset().top;
			
			
		// if visible, start up map and stop checking scroll
		if (winScroll + winHeight > mapTop) {
			
			loadMapScript();
			$win.off('scroll', checkScroll);
			
		} 	
	}
	
	$win.on('scroll', checkScroll);
	checkScroll();
			
	
	function loadMapScript() {
	
		var script = document.createElement("script");
		script.type = "text/javascript";
		script.src = "../maps.googleapis.com/maps/api/js@key=AIzaSyAZE1KbNaRAjmb6mSN8LZTxE34cX1wC2mU&sensor=true&callback=home_map_initialize";
		document.body.appendChild(script);	
	
	}		
	
	// attach to window for callback
	window.home_map_initialize = function() {
		console.log('google loaded');
		
		google.maps.visualRefresh = true;
		
		
		var
			dtsLocations = {},
			worldZoom = 1,
			worldCenter = mapCenter = new google.maps.LatLng(40,0),
			
			//usCenter = new google.maps.LatLng(38.134557,-98.876953),
			usCenter = new google.maps.LatLng(38.134557,-107.876953),
			usZoom = 3,
			
			// CREATE MAP
			mapOptions = {
				scrollwheel: false, // disable so page scrolling works better	
				zoom: usZoom,
				center: usCenter,
				mapTypeId: google.maps.MapTypeId.ROADMAP
				/*,styles:[
					{
						featureType: "water",
						stylers: [
							{ hue: "#6e00ff" }
						]
					}
				]
				*/
			},
			map = new google.maps.Map(mapBlock, mapOptions),
			currentWindow = new google.maps.InfoWindow({
					content: 'empty'
				}),
			locationsDom = $('#home-locations'),

			/* search stufff */
			sidebar = $('#map-sidebar'),
			resultsDom = $('#map-list-results'),
			mapBackToCampuses = $('#map-back-to-campuses'),
			mapBackToChurches = $('#map-back-to-churches'),
			mapSearchInput = $('#map-search-input'),
			mapSearchButton = $('#map-search-button'),
			moreMaps = $('#moremaps');	
			
			
		// add DTS locations
		locationsDom.find('.location').each(function(i, el) {
			
			var locationDom = $(this),
				
				location = {
					id: locationDom.attr('id'),
					name: locationDom.find('strong').first().html(),
					lat: parseFloat(locationDom.attr('data-lat')),
					lng: parseFloat(locationDom.attr('data-lng')),
					content: locationDom.find('.content').html(),
					image: locationDom.attr('data-image'),
					url: locationDom.find('a').first().attr('href'),
					//icon: locationDom.attr('data-icon') || '//dtsi.s3.amazonaws.com/resources/maps/map-icon-campus.png'
					icon: locationDom.attr('data-icon') || '//dtsi.s3.amazonaws.com/resources/maps/map-icon-campus@2x.png'
				};
				
				
			dtsLocations[location.id] = location;
			
			// create pin and window	
			createWindow(location);
		});	
		
		function createWindow(location) {
			var	marker =
					new google.maps.Marker({
						position: new google.maps.LatLng(location.lat, location.lng),
						title: location.name,
						map: map,
						//icon: location.icon,
						icon: new google.maps.MarkerImage(location.icon, new google.maps.Size(32, 37), null, null, new google.maps.Size(32, 37)),
						zIndex: 10000 // on top of churches
					});
			
			google.maps.event.addListener(marker, 'click', function(event) {
			
				
				openLocation(location);
									
				// find the church/location in the side window 
				var selectedLocation = $('#' + location.id);
				
				// highlight it and unhighlight others
				selectedLocation
					.addClass('selected')
					.siblings()
						.removeClass('selected');
						
				// for churches, scroll to it
				if (selectedLocation.parent().attr('id') == 'map-list-results') {
					resultsDom.animate({scrollTop:selectedLocation.offset().top - resultsDom.offset().top + resultsDom.scrollTop()}, 'slow');
				}
				
				//showMoreMaps();
			});	
			
			location.marker = marker;
			
			return marker;
		}	
		
		function openLocation(location) {

			currentWindow.setPosition(new google.maps.LatLng(location.lat, location.lng));
			currentWindow.setContent(
				'<div class="map-info">' +
					'<div class="campus">' +
						'<a href="' + location.url + '" class="campuslink">' +
							'<img src="' + location.image + '" width="300" height="85" alt="' + location.name + '" />' +
							'<h3>' + location.name + '</h3>' + //<span class="url">dts.edu/' + key + '</span>
						'</a>' +
						location.content +
					'</div>',					
				'</div>'
			);
			currentWindow.open(map, location.marker);
		
			
		}	
		
		// user clicks a sidebar campus		
		locationsDom.on('click', '.location', function(e) {
			
			e.preventDefault();
			
			var locationEl = $(this).addClass('selected'),
				location = dtsLocations[locationEl.attr('id')];
		
			locationEl.siblings().removeClass('selected');	
			
			if (typeof _gaq != 'undefined' && locationEl.parent().attr('id') == 'home-locations') {
				_gaq.push(['_trackEvent', 'HomeMap', 'Campus', locationEl.attr('id')]);
			}
			
			openLocation(location);
			
			//showMoreMaps();
			
			return false;
		});	
		
		
		
		
		/* ONLINE PIN LOCATION */
		var checkedGeoCode = false;
		function findUsersLocation() {
				
			//getLocationFromGeocode();
			getLocationFromIp();
			
		}
		
		findUsersLocation();		
		
		function getLocationFromGeocode() {
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(
					// success
					function(position) {
						checkedGeoCode = true;
						moveOnlinePin(position.coords.latitude, position.coords.longitude);
					},
					// error
					function() {
						getLocationFromIp();
					});
				
				setTimeout(function() {
					if (!checkedGeoCode)
						getLocationFromIp();
				}, 5000);
			} else {
				// browser without geolocation API
				getLocationFromIp();
			}	
		}
		
		function getLocationFromIp() {
			
			if (checkedGeoCode)
				return;
			
			// get from cookie?
			var location = getLocationCookie();
			
			console.log('location cookie', location);
			
			if (location != null) {
				moveOnlinePin(location.lat, location.lng);
				checkedGeoCode = true;
			} else {
			
				// find location and move online ed pin?
				var apiurl = '//api.ipinfodb.com/v3/ip-city/?key=209b76d7867bf494cf91cf58c7045cd43ed922aa9031a17be2626eca8eee8447&format=json&callback=?&ip=' + $mapContainer.attr('data-userip');
				$.getJSON(apiurl,
					  function(data) {
						
					   var 
							lat = parseFloat(data.latitude),
							lng = parseFloat(data.longitude);
							
						console.log('location data', $mapContainer.attr('data-userip'), data);							
							
						setLocationCookie({lat: lat, lng: lng});
						
						moveOnlinePin(lat, lng);
						
						checkedGeoCode = true;
					}
				);
			}
		}
		
		function getLocationCookieName() {		
			return geocookieName = 'geo-' + $mapContainer.attr('data-userip');
		}
		
		function getLocationCookie() {
			
			var location = getCookie(getLocationCookieName());
			
			if (location != '')
				return JSON.parse(location);
			else
				return null;
		}
		
		
		function setLocationCookie(location) {
			return setCookie(getLocationCookieName(), JSON.stringify(location), 100);
		}
		
		
		//Set the cookie
		function setCookie(c_name, value, expire) {
			var exdate=new Date();
			exdate.setDate(exdate.getDate()+expire);
			document.cookie = c_name+ "=" +escape(value) + ((expire==null) ? "" : ";expires="+exdate.toGMTString());
		}
		
		//Get the cookie content
		function getCookie(c_name) {
			if (document.cookie.length > 0 ) {
				c_start=document.cookie.indexOf(c_name + "=");
				if (c_start != -1){
					c_start=c_start + c_name.length+1;
					c_end=document.cookie.indexOf(";",c_start);
					if (c_end == -1) {
						c_end=document.cookie.length;
					}
					return unescape(document.cookie.substring(c_start,c_end));
				}
			}
			return '';
		}
		
		function moveOnlinePin(lat, lng) {
			
			if (lat == 0 && lng == 0 ) {
				lat = 21.3114;
				lng = -157.7964; // hawaii
			}
			
			
			
			var location = dtsLocations['online'],
				 newPos = new google.maps.LatLng(lat, lng),
				 dist = 0.5,
				 nearCampus = false;
				 
			/*
			 // do a little check here to see if we're outside one of the campus cities...?
			 for (var key in dtsLocations) {
				 if (key.substring(0,3) != 'loc') {
					 var campus = dtsLocations[key];
					 
					 if (lat < campus.lat + dist &&
						 lat > campus.lat - dist &&
						 lng < campus.lng + dist &&
						 lng > campus.lng - dist ) {
						 
						 nearCampus = true;
						 break;
					 }
				 }
			 }
			*/
			
			location.lat = lat;
			location.lng = lng;
			 
			 if (!nearCampus)
				 location.marker.setPosition(newPos);	
		}					
		
		
	}

});
