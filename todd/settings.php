<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.sms_gateway.php');

$user_id = get_param('user_id');
if (!$user_id) {
    redirect('/login.php');
}
$user = new User($user_id);
$carriers = SMS_Gateway::get_all();

$html = new HTML();
$html->set_title('Settings');
$html->generate_header_mobile();
?>
<div id="settings_display" data-role="page" data-title='Meocracy' data-theme='a'>
    <div data-role='header' data-theme='a'>
        <div data-role='navbar'>

            <ul>
            <li><a href='index.php' data-icon='home' data-theme='a'>Home</a></li>
            <li><a href='search.php' data-icon='search' data-theme='a'>Search</a></li>

            <li><a href='alert_manager.php' data-icon='alert' data-theme='a'>Alerts</a></li>
            <li><a href='settings.php' data-icon='gear' data-theme='a'>Settings</a></li>
            </ul>

        </div>
    </div><!-- /header -->


    <div id="content" data-role='content'>
        <div>
            <ul data-role="listview" data-theme="a">
                <li data-theme="e"><a href="#settings_input">Contact Settings</a></li>
                <li data-theme="b">Name: <span id="settings_name"></span></li>
                <li data-theme="b">Mobile #: <span id="settings_phone"></span></li>
                <li data-theme="b">Carrier: <span id="settings_carrier"></span></li>
                <li data-theme="e"><a href="#settings_zip">Location Settings</a></li>
                <li data-theme="b">City: <span id="settings_city"></span></li>
                <li data-theme="b">County: <span id="settings_county"></span></li>
                <li data-theme="b">State: <span id="settings_state"></span></li>
            </ul>
        </div>
    </div>
</div>

<div id="settings_input" data-role='page' data-title='Meocracy' data-theme='a'>
    <div data-role='header' data-theme='a'>
        <div data-role='navbar'>

            <ul>
            <li><a href='index.php' data-icon='home' data-theme='a'>Home</a></li>
            <li><a href='search.php' data-icon='search' data-theme='a'>Search</a></li>

            <li><a href='alert_manager.php' data-icon='alert' data-theme='a'>Alerts</a></li>
            <li><a href='settings.php' data-icon='gear' data-theme='a'>Settings</a></li>
            </ul>

        </div>
    </div><!-- /header -->

    <div data-role='content'>
        <form>
            <div data-role="fieldcontain">
                <label for="person_last">Last Name</label>
                <input type="text" id="person_last" value=""/>
            </div>
            <div data-role="fieldcontain">
                <label for="person_first">First Name</label>
                <input type="text" id="person_first" value=""/>
            </div>
            <div data-role="fieldcontain">
                <label for="person_phone">Phone</label>
                <input type="text" id="person_phone" value=""/>
            </div>
            <div data-role="fieldcontain">
                <label for="person_sms_id">Mobile service provider</label>
                <select id="person_sms_id" name="person_sms_id">
                    <option value=""></option>
<?php
if (!empty($carriers)) {
    foreach ($carriers as $c) {
        echo '<option value="' . $c->id() . '">' . $c->carrier() . '</option>';
    }
}
?>
                </select>
            </div>
            <div data-role="fieldcontain">
                <a href="#settings_zip" data-role="button">Save</a>
            </div>
            <div data-role="fieldcontain">
                <a href="#settings_display" data-role="button">Cancel</a>
            </div>
        </form>
    </div>
</div>

<div id="settings_zip" data-role='page' data-title='Meocracy' data-theme='a'>
    <div data-role='header' data-theme='a'>
        <div data-role='navbar'>

            <ul>
            <li><a href='index.php' data-icon='home' data-theme='a'>Home</a></li>
            <li><a href='search.php' data-icon='search' data-theme='a'>Search</a></li>

            <li><a href='alert_manager.php' data-icon='alert' data-theme='a'>Alerts</a></li>
            <li><a href='settings.php' data-icon='gear' data-theme='a'>Settings</a></li>
            </ul>

        </div>
    </div><!-- /header -->

    <div data-role='content'>
        <div>
            <span id="place_name"></span><br/>
            <span id="place_phone"></span><br/>
            <span id="place_email"></span><br/>
        </div>
        <form>
            <div data-role="fieldcontain">
                <label for="place_zip">Zip</label>
                <input type="text" id="place_zip" value=""/><br/>
            </div>
            <div data-role="fieldcontain">
                <a href="#choose_city" data-role="button" data-rel="dialog" data-transition="pop">Select City</a>
            </div>
            <div data-role="fieldcontain">
                <a href="#settings_display" data-role="button">Cancel</a>
            </div>
        </form>
    </div>
</div>

<div id="choose_city" data-role="page" data-title='Meocracy' data-theme='a'>
    <div data-role="header" data-theme="a"></div>

    <div id="content" data-role='content'>
        <ul id="city_list" data-role='listview' data-inset='false' data-theme='a'  data-divider-theme='a'></ul>
        <a href="#settings_zip">Different Zip</a>
    </div>
</div>

<script type="text/javascript">
var Settings = {
    name: null,
    first: null,
    last: null,
    phone: null,
    carrier: null,
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
        Settings.carrier = data.carrier;
        Settings.zip = data.zip;
        Settings.city_name = data.city_name;
        Settings.county_name = data.county_name;
        Settings.state_name = data.state_name;
        console.log(Settings);
        if (!Settings.phone && !Settings.city_name) {
            console.log('Being directed to #settings_input');
            // Must be first time in:  start with data input form.
            $.mobile.changePage("#settings_input");
            Settings.showInputSettings();
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
        Settings.email = data.email;
        $('#place_name').html(Settings.name);
        $('#place_phone').html(Settings.phone);
        $('#place_email').html(Settings.email);        
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
    $('#person_sms_id').val(Settings.sms_id);
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
    $('#settings_carrier').html(Settings.carrier);
    $('#settings_city').html(Settings.city_name);        
    $('#settings_county').html(Settings.county_name);        
    $('#settings_state').html(Settings.state_name);        
    $.mobile.hidePageLoadingMsg();
};

Settings.call = {
    loadSettings: function() {
        console.log('Settings.call.loadSettings');
        $.mobile.showPageLoadingMsg();
        var url = Constants.server + Constants.page.settings;
        $.ajax({
            url: url,
            dataType: 'jsonp',
            data: '',
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
            $('#person_email').val() != Settings.email
        );
    }
};

Settings.initialize = function() {
    Settings.call.loadSettings();
};

$(document).delegate('#settings_input', 'pageshow', function() {
    console.log('pageshow #settings_input fired; ' + Settings.phone);
    Settings.showInputSettings();
});

$(document).delegate('#settings_zip', 'pageshow', function(e) {
    console.log('pageshow #settings_zip fired');
    if (Settings.dataChanged('person')) {
        console.log('person data changed.');
        Settings.call.savePerson();    
    }
    else {
        console.log('person data not changed.');
        Settings.showZipInputSettings();
    }
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

$(document).ready(function() {
console.log('preparing to initialize settings page.');
    Settings.initialize(); // JavaScript specific to Settings page
});

</script>
<?php
$html->generate_footer_mobile();
?>
