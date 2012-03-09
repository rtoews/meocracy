var Settings = {
    name: null,
    first: null,
    last: null,
    phone: null,
    city_id: null,
    city_name: null,
    county_name: null,
    state_name: null
};

Settings.error = function(jqXHR, textStatus, errorThrown) {
};

Settings.callback = {
    settingsDisplayHandler: function(data) {
        console.log('Settings.callback.settingsDisplayHandler');
        Settings.name = data.name;
        Settings.first = data.first;
        Settings.last = data.last;
        Settings.phone = data.phone;
        Settings.sms_id = data.sms_id;
        Settings.zip = data.zip;
        Settings.city_name = data.city_name;
        Settings.county_name = data.county_name;
        Settings.state_name = data.state_name;
        console.log(Settings);
        if (!Settings.phone) {
            // Must be first time in:  start with data input form.
            $.mobile.changePage("#settings_input");
            Settings.showInputSettings();
        }
        else if (!Settings.city_name) {
            console.log('Being directed to #settings_zip');
            // Must be first time in:  start with data input form.
            $.mobile.changePage("#settings_zip");
            Settings.showZipInputSettings();
        }
        else {
            console.log('Being directed to #settings_display');
            $.mobile.changePage("#settings_display");
            // In case #settings_display is already in focus.
            Settings.showSettings();
        }
    },

    zip: function(data) {
        Settings.name = data.name;
        Settings.phone = data.phone;
        $('#place_name').html(Settings.name);
        $('#place_phone').html(Settings.phone);
        $.mobile.hidePageLoadingMsg();
    },

    cityList: function(data) {
        var el;
            $('#city_list').html('');
            for (var i = 0, j = data.length; i < j; i++) {
                var el = '';
                el += '<li>';
                el += '    <a id="' + data[i].key + '" data-name="' + data[i].value + '" href="#">';
                el += '        ' + data[i].value;
                el += '    </a>';
                el += '</li>';
    
                $('#city_list').append(el);        
            }
            $('#city_list a').click(Settings.call.saveCity);
            $('#city_list').listview('refresh');
        $.mobile.hidePageLoadingMsg();
    },
};

Settings.showInputSettings = function() {
console.log('Showing input settings: ' + Settings.phone);
    $('#person_last').val(Settings.last);
    $('#person_first').val(Settings.first);
    $('#person_phone').val(Settings.phone);
    $.mobile.hidePageLoadingMsg();
};

Settings.showZipInputSettings = function() {
console.log('showZipInputSettings');
    $('#place_name').html(Settings.name);
    $('#place_phone').html(Settings.phone);
    $('#place_zip').val(Settings.zip);
};

Settings.showSettings = function(data) {
    console.log('Settings.showSettings');
    Settings.showInputSettings();
    $.mobile.changePage('#settings_display');
    $('#settings_name').html(Settings.name);
    $('#settings_phone').html(Settings.phone);
    $('#settings_city').html(Settings.city_name);        
    $('#settings_county').html(Settings.county_name);        
    $('#settings_state').html(Settings.state_name);        
    $.mobile.hidePageLoadingMsg();
};

Settings.call = {
    loadSettings: function(first) {
        console.log('Settings.call.loadSettings');
        $.mobile.showPageLoadingMsg();
        var url = Constants.server + Constants.page.settings;
        var data = first ? 'mode=1' : '';
        $.ajax({
            url: url,
            dataType: 'jsonp',
            data: data,
            success: Settings.callback.settingsDisplayHandler,
            error: Settings.error
        });
    },

    savePerson: function() {
        $.mobile.showPageLoadingMsg();
        var url = Constants.server + Constants.process.save_settings;
        var _fields = [];
        _fields.push('p=1');
        _fields.push('first=' + encodeURIComponent($('#person_first').val()));
        _fields.push('last=' + encodeURIComponent($('#person_last').val()));
        _fields.push('phone=' + encodeURIComponent($('#person_phone').val()));
        _fields.push('sms_id=' + encodeURIComponent($('#person_sms_id').val()));
        var data = _fields.join('&');
console.log('savePerson: ' + data);
        $.ajax({
            url: url,
            dataType: 'jsonp',
            data: data,
            success: Settings.callback.zip,
            error: Settings.error
        });
    },

    saveCity: function() {
        if ($(this).attr('id')) {
            Settings.city_id = $(this).attr('id');
            Settings.city_name = $(this).attr('data-name');
            $.mobile.showPageLoadingMsg();
        }
        else {
            Settings.city_id = arguments[0];
            Settings.city_name = arguments[1];
        }
        var url = Constants.server + Constants.process.save_settings;
        var _fields = [];
        _fields.push('p=2');
        _fields.push('city_id=' + encodeURIComponent(Settings.city_id));
        var data = _fields.join('&');
        $.ajax({
            url: url,
            dataType: 'jsonp',
            data: data,
            success: Settings.call.loadSettings,
            error: Settings.error
        });
    }
};

Settings.dataChanged = function(area) {
    if (area == 'person') {
        return (
            $('#person_last').val() != Settings.last ||
            $('#person_first').val() != Settings.first ||
            $('#person_phone').val() != Settings.phone ||
            $('#person_sms_id').val() != Settings.sms_id
        );
    }
};

Settings.initialize = function() {
    Settings.call.loadSettings(true);
};

$(document).delegate('#settings_input', 'pageshow', function() {
    console.log('pageshow #settings_input fired; ' + Settings.phone);
    Settings.showInputSettings();
});

$(document).delegate('#choose_city', 'pageshow', function() {
    console.log('pageshow #choose_city fired.');
    var zip = $('#place_zip').val();
    if (zip.length == 5) {
        $.mobile.showPageLoadingMsg();
        var url = Constants.server + Constants.popup.city_select;
        $.ajax({
            url: url,
            dataType: 'jsonp',
            data: 'zip=' + zip,
            success: Settings.callback.cityList,
            error: Settings.error
        });
    }
});

$(document).delegate('#save_city', 'pageshow', function(e, data) {
    console.log('pageshow #save_city fired.');
});

$(document).delegate('#settings_display', 'pageshow', function(e, data) {
    console.log('pageshow #settings_display fired.');
    Settings.showSettings();
});

$(document).delegate('#settings_display', 'pageinit', function() {
console.log('pageinit fired; preparing to initialize settings page.');
    Settings.initialize(); // JavaScript specific to Settings page
});
