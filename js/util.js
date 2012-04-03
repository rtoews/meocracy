    String.prototype.str_replace = function(from, to) {
        if (from.length != to.length) {
            return;
        }
        var tmp = this;
        for (var i = 0, j = from.length; i < j; i++) {
            tmp = tmp.replace(from[i], to[i]);
        }
        return tmp;
    };

    var MeoStorage = {
        get_user: function() {
            var _user = JSON.parse(localStorage.getItem('meo_user')) || {};
            _user.user_id = _user.user_id || '';
            _user.phone = _user.phone || '';
            return _user || {};
        },

        set_user: function(data) {
            var _user = JSON.stringify(data);
            localStorage.setItem('meo_user', _user);
            App = window.App || {};
            App.user_id = data.user_id;
            App.card = 'meo_user_id=' + data.user_id;
        },

        get_session: function() {
            var _sess = JSON.parse(localStorage.getItem('meo_session'));
            return _sess;
        },

        set_session: function(data) {
            var _sess = JSON.stringify(data);
            localStorage.setItem('meo_session', _sess);
        },

        get_fyi: function() {
            var _fyi = JSON.parse(sessionStorage.getItem('meo_fyi'));
            return _fyi || {};
        },

        set_fyi: function(data) {
            var _fyi = JSON.stringify(data);
            sessionStorage.setItem('meo_fyi', _fyi);
        },

        clear : function() {
            localStorage.clear();
        },
    };

    var MeoData = {};
/*
    MeoData = function() {


        var retrieveData = function() {
console.log('retrieveData');
            $.ajax({
                url: Constants.server + Constants.page.index,
                dataType: 'jsonp',
                data: App.card,
                success: MeoData.callback,
                error: meo.error
                });
        };

        var _user = MeoStorage.get_user();
        var user_id = _user && _user.user_id || '';
        if (!user_id) {
            login();
            return false; // ensure processing doesn't continue, since user_id isn't yet available.
        }

        var data = MeoStorage.get_session();
        if (!data || data && (!data.visited || !data.region)) {
            retrieveData();
            return false;
        }
        else {
//console.log('Do not need to retrieve data.');
            return data;
        }
    };

    MeoData.callback = function(data) {
        if (data.status == -1) {
            login();
        }
        else {
            var _fyi = MeoStorage.get_fyi();
            var _fn = meo.callback.save_city;
            util.setSessionData(data);
            if (_fyi.page) {
                if (_fyi.issue_type == Constants.ANNOUNCEMENT_TYPE) {
                    _fn = meo.call.announcement;
                }
                else {
                    _fn = meo.call.legislation;
                }
            }
            _fn();
        }
    };
*/

    MeoData.set = function(data) {
        MeoStorage.set_session(data);
    };

    MeoData.setVisited = function(region, type) {
        var _data = MeoStorage.get_session();
        var visited = _data.visited;
        if (!visited[region]) {
            visited[region] = 0;
        }
        var firstVisit = visited[region] & type ? false : true;
        if (firstVisit) {
            visited[region] |= type;
            // save "visit" to user record.
        }
        return visited;
    };

    MeoData.setProp = function(prop, val) {
        if (!val) return false;
        var data = MeoStorage.get_session() || {};
        var levels = prop.split('.');
        var obj = data;
        var _setCmd;
        for (var i = 0, j = levels.length; i < j; i++) {
            if (!obj[levels[i]]) {
                obj[levels[i]] ={};
            }
            obj = obj[levels[i]];
        }
        if (typeof val == 'number') {
            _setCmd = 'data.' + prop + '=' + val;
        }
        else {
            _setCmd = 'data.' + prop + '="' + val + '"';
        }
        eval(_setCmd);
        MeoStorage.set_session(data);
        if (!val) return true;
    };

    MeoData.getProp = function(prop, data) {
        var levels = prop.split('.');
        var obj = data;
        var _getCmd;
        var missing = false;
        var val = '';
        for (var i = 0, j = levels.length; !missing && i < j; i++) {
            if (!obj[levels[i]]) {
                missing = true;
            }
            else {
                obj = obj[levels[i]];
            }
        }
        if (!missing) {
            _getCmd = 'val = data.' + prop;
            eval(_getCmd);
        }
        return val;
    };

/*
    Utility functions
*/
    var util = {};

    util.monthNames = ['January','February','March',    'April',  'May',     'June',
                       'July',   'August',  'September','October','November','December'];

    util.login = function() {
console.log('util.login');
        $.mobile.changePage('#index_city');
    };

    util.logout = function() {
        MeoStorage.clear();
        $.mobile.changePage('index.html');
    };

    util.error = function(jqXHR, textStatus, errorThrown) {
        console.log('Error: ' + textStatus);
        console.log(errorThrown);
        console.log(jqXHR);
    };

    /*
        updateDictionary is for setting data that change with announcement / legislation selections.
    */
    util.updateDictionary = function(data) {
console.log('util.updateDictionary');
        var _sponsors = MeoData.getProp('sponsors', data);
        var _sponsor = _sponsors.length >= 1 ? _sponsors[0] : {};
        MeoData.setProp('issue.category.id', MeoData.getProp('category.id', data));
        MeoData.setProp('issue.category.name', MeoData.getProp('category.name', data));
        MeoData.setProp('issue.type', MeoData.getProp('type', data));
        MeoData.setProp('issue.id', MeoData.getProp('id', data));
        MeoData.setProp('issue.image', MeoData.getProp('image', data));
        MeoData.setProp('issue.title', MeoData.getProp('title', data));
        MeoData.setProp('issue.location', MeoData.getProp('location', data));
        MeoData.setProp('issue.bill_id', MeoData.getProp('bill_id', data));
        MeoData.setProp('issue.sponsor.link', '');
        MeoData.setProp('issue.sponsor.name', MeoData.getProp('name', _sponsor));
        MeoData.setProp('issue.sponsor.lastname', MeoData.getProp('lastname', _sponsor));
        MeoData.setProp('issue.sponsor.image', MeoData.getProp('image', _sponsor));
        MeoData.setProp('issue.sponsor.position', MeoData.getProp('position', _sponsor));
        MeoData.setProp('issue.sponsor.title', MeoData.getProp('title', _sponsor));
        MeoData.setProp('issue.sponsor.data', '');
        MeoData.setProp('announcement.link', 'announcement.html?id=' + data.id);
        MeoData.setProp('announcement.image', MeoData.getProp('image', data));
        MeoData.setProp('announcement.title', MeoData.getProp('title', data));
        MeoData.setProp('announcement.data', '');
    };

    util.setClassTags = function() {
console.log('util.setClassTags');
        var _data = util.getSessionData();
console.log(_data);
        var _county_state = _data.region[Constants.REGION_COUNTY].name + ' County, ' + _data.region[Constants.REGION_STATE].name;
        var _issue_page = _data.issue.type == Constants.ANNOUNCEMENT_TYPE ? 'announcement.html' : 'legislation.html';
        $('.city_link').attr('src', 'index.html');
        $('.city_seal').attr('src', _data.region[Constants.REGION_CITY].image);
        $('.city').html(_data.region[Constants.REGION_CITY].name);
        $('.county_state').html(_county_state);
        $('.issue_link').attr('href', _issue_page + '?' + _data.issue.id);
        $('.issue_image').attr('src', _data.issue.image);
        $('.issue_title').html(_data.issue.title);
        $('.issue_location').html(_data.issue.location);
        $('.announcement_link').attr('href', MeoData.getProp('announcement.link', _data));
        $('.announcement_image').attr('src', MeoData.getProp('announcement.image', _data));
        $('.announcement_title').html(MeoData.getProp('announcement.title', _data));
        $('.legislation_link').attr('href', MeoData.getProp('legislation.link', _data));
        $('.legislation_image').attr('src', MeoData.getProp('legislation.image', _data));
        $('.legislation_title').html(MeoData.getProp('legislation.title', _data));
        $('.sponsor_link').attr('src', '');
        $('.sponsor_image').attr('src', _data.issue.sponsor.image);
        $('.sponsor_name').html(_data.issue.sponsor.name);
        $('.sponsor_lastname').html(_data.issue.sponsor.lastname);
        $('.sponsor_position').html(_data.issue.sponsor.position);
        $('.sponsor_title').html(_data.issue.sponsor.title);
        $('.category_name').html(MeoData.getProp('issue.category.name', _data)).attr('href', 'issues.html?id=' + MeoData.getProp('issue.category.id', _data));
    };

    util.getSessionData = function() {
        var _sess = MeoStorage.get_session();
        return _sess;
    };

    util.setSessionData = function(data) {
console.log('util.setSessionData');
console.log(data);
        var _locationName = [
            data.region.city.name,
            data.region.county.name + ' County',
            data.region.state.name,
        ];
        var _data = { 
            visited : data.visited || [],
            header : {
                type: Constants.HEADER_GENERAL,
                imgSrc: data.region.city.image,
                location: _locationName.join(', '),
            },
            region : {
                4: {
                    id: data.region.city.id,
                    name: data.region.city.name,
                    image: data.region.city.image,
                    a_issues: parseInt(data.region.city.announcement_count),
                    l_issues: parseInt(data.region.city.legislation_count),
                },
                3: {
                    id: data.region.county.id,
                    name: data.region.county.name,
                    image: data.region.county.image,
                    a_issues: parseInt(data.region.county.announcement_count),
                    l_issues: parseInt(data.region.county.legislation_count),
                },
                2: {
                    id: data.region.state.id,
                    name: data.region.state.name,
                    image: data.region.state.image,
                    a_issues: parseInt(data.region.state.announcement_count),
                    l_issues: parseInt(data.region.state.legislation_count),
                },
            },
            issue : data.issue,
        };
        MeoStorage.set_session(_data);
    };

    util.buildData = function(data) {
        var _d = data + '&';
        if (App) {
            _d += App.card;
        }
        else {
            var _u = MeoStorage.get_user();
            _d += 'meo_user_id=' + (_u && _u.user_id || '');
        }
        return _d;
    };

    util.serverCall = function(url, data, callback) {
        data = util.buildData(data);
//console.log(url+'?'+data);
        $.ajax({
            url: url,
            dataType: 'jsonp',
            data: data,
            success: callback,
            error: util.error
        });
    };

    util.check_phone = function() {
        var _user = MeoUser();
        if (_user && _user.phone) {
            return true;
        }
        else {
            return false;
        }
    };

    util.valPhone = function(str) {
        var pattern = /^\d{10}$/;
        return str.match(pattern);
    };

    util.fmtDate = function(str) {
        var fmt = '';
        if (str) {
            var ymd = str.split('-');
            fmt = util.monthNames[ymd[1]-1] + ' ' + ymd[2] + ', ' + ymd[0];
        }
        return fmt;
    };

    util.fmtPending = function(count) {
        var text;
        if (count == 0) {
            text = 'No issues';
        }
        else if (count == 1) {
            text = count + ' issue';
        }
        else {
            text = count + ' issues';
        }
        return text;
    };

    util.queryString = function(param) {
        var q = location.href.substr(location.href.lastIndexOf('?') + 1);
        var kv = q.split('&');
        var v = null;
        for (var i = 0; i < kv.length; i++) {
            var pair = kv[i].split('=');
            var key = unescape(pair[0]);
            if (key == param) {
                v = pair[1];
            }
        }
        return arguments.length == 0 ? q : v;
    };

/*
    util methods for possible entrances:
    * Guest pass (using SMS Tell Friend link):   
        util.checkForGuestPass:  check URL for guest pass code; return code.
        util.handleGuestPass:  call AJAX fyi.php.
        util.guestPass_callback:  store available guest data:
            user ID, phone
            region, issue data
    * First-time visitor:
    * Repeat visitor:
*/
    util.checkForGuestPass = function() {
        var url = location.href;
        var pattern = /\/fyi\/([a-z0-9]{10})$/;
        var guest_pass = '';
        var match;
        if (match = pattern.exec(url)) {
            guest_pass = match[1];
        }
        return guest_pass;
    };

    util.handleGuestPass = function(guest_pass) {
console.log('util.handleGuestPass');
        // Determine whether the friend already has a user I.
        var _user = MeoStorage.get_user();
        var data = 'code=' + guest_pass + '&meo_user_id=' + _user.user_id;
        var url = Constants.server + Constants.page.fyi;
        $.ajax({
            url: url,
            dataType: 'jsonp',
            data: data,
            success: util.guestPass_callback,
            error: util.error
        });
    };

    util.guestPass_callback = function(data) {
console.log('util.guestPass_callback');
console.log(data);
        var _user = MeoStorage.get_user();
        var newPage;
        var issueType;
        if (data.user_id) {
            _user.user_id = data.user_id;
            _user.phone = data.phone;
            MeoStorage.set_user(_user);
            util.setSessionData(data);
            if (data.issue.type == Constants.ANNOUNCEMENT_TYPE) {
                newPage = '/announcement.html?id='+data.issue.id;
                issueType = Constants.ANNOUNCEMENT_TYPE;
            }
            else if (data.issue.type == Constants.LEGISLATION_TYPE) {
                newPage = '/legislation.html?id='+data.issue.id;
                issueType = Constants.LEGISLATION_TYPE;
            }
            var _fyi = {
                page: newPage,
                issue: data.issue,
            };
            MeoStorage.set_fyi(_fyi);
        }
    };

    util.checkUserId = function() {
console.log('util.checkUserId');
        var _user = MeoStorage.get_user();
        var data = 'meo_user_id='+ _user.user_id;
        var url = Constants.server + Constants.page.check_user_id;
console.log(url+'?'+data);
        $.ajax({
            url: url,
            dataType: 'jsonp',
            data: data,
            success: util.checkUserId_callback,
            error: util.error
        });
    };

    util.checkUserId_callback = function(data) {
console.log('util.checkUserId_callback');
console.log(data);
        var _user = MeoStorage.get_user();
        if (data.user_id != _user.user_id) {
            // This really shouldn't happen, but just in case it does--local storage erased, user record erased, for instance.
            MeoStorage.clear();
        }
        if (data.user_id) {
            _user.user_id = data.user_id
            MeoStorage.set_user(_user);
            util.setSessionData(data);
        }
        else {
            // check_user_id.php didn't return user_id for some reason.
        }
    };

/*
    Object in which to store app-specific data.
*/
    if (Constants.phonegap == true) {
        device = window.device || { uuid : '7886d8f0bd2ff12decf9c59e16e9f023e076eb7c' };

        var App = {
            uuid : device.uuid,   // phonegap device.uuid
            card : 'uuid=' + device.uuid,
            user_id : user_id,
        };
    }
    else {
        var code = util.checkForGuestPass();
        if (code) {
            // Coming in as guest
            util.handleGuestPass(code);
        }
        else {
            // Coming in as new or repeat user.
            util.checkUserId();
        }
    }

