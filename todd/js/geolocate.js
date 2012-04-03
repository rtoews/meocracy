var Geo = {
    getZip : {
        getLocation: function(){
            console.log('geolocation: searching...');
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(Geo.getZip.getZipCode, Geo.getZip.error, {timeout: 7000});//cache it for 10 minutes
            } else {
                Geo.getZip.error('Geo location not supported');
            }
        },

        index: 0,

        error: function(msg) {
            if (msg.code) {
            //this is a geolocation error
                switch (msg.code) {
                    case 1:
                        console.log('ERROR: Permission Denied');
                        break;
                    case 2:
                        console.log('ERROR: Position Unavailable');
                        break;
                    case 3:
                        Geo.getZip.index++;
                        console.log('ERROR: Timeout... Trying again (' + Geo.getZip.index + ')');
                        navigator.geolocation.getCurrentPosition(Geo.getZip.getZipCode, Geo.getZip.error, {timeout: 7000});
                        break;
                    default:
               //nothing
                }
            } else {
            //this is a text error
                console.log('ERROR: ...but no msg.code');
            }
 
        },
 
        getZipCode: function(position) {
            var position = position.coords.latitude + "," + position.coords.longitude;
            $.getJSON('proxy.php', {
                    path : "http://maps.google.com/maps/api/geocode/json?latlng="+position+"&sensor=false",
                    type : "application/json"
            }, function(json) {
            //Find the zip code of the first result
                if (!(json.status == "OK")) {
                    Geo.getZip.error('Zip Code not Found');
                    return;
                }
                var found = false;
                $(json.results[0].address_components).each(function(i, el) {
                    if ($.inArray("postal_code", el.types) > -1) {
                        meo.call.index_city(el.short_name);
                        found = true;
                        return;
                    }
                });
                if(!found){
                    Geo.getZip.error('Zip Code not Found');
                }
            });
        }
    }
};


