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
            var _user = JSON.parse(localStorage.getItem('meo_user'));
//console.log('MeoStorage.get_user');
            return _user || {};
        },

        set_user: function(data) {
console.log('MeoStorage.set_user');
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

/*
        get_user_id : function() {
            var user_id = localStorage.getItem('meo_user_id');
            user_id = !user_id || isNaN(user_id) ? '' : parseInt(user_id);
            return user_id;
        },

        set_user_id : function(user_id) {
//console.log('MeoStorage.set_user_id');
            App = window.App || {};
            if (App) {
                App.user_id = user_id;
                App.card = 'meo_user_id=' + user_id;
            }
            localStorage.setItem('meo_user_id', user_id);
        },

        get : function(key) {
            var data = JSON.parse(localStorage.getItem(key));
            return data;
        },

        set : function(key, data) {
            var data = JSON.stringify(data);
            localStorage.setItem(key, data);
        },
*/

        clear : function() {
            localStorage.clear();
        },
    };

    var MeoData = function() {
        var fn = arguments.callee.caller;

        MeoData.callback = function(data) {
//console.log('***** MEODATA.CALLBACK *****');
            if (data.status == -1) {
                login();
            }
            else {
                var _fyi = MeoStorage.get_fyi();
                var _fn = meo.callback.save_city;
console.log('MeoData.callback, default function:');
console.log(_fn);
                var _locationName = [
                    data.city.name,
                    data.county.name + ' County',
                    data.state.name,
                ];
                var _data = { 
                    visited : data.visited || [],
                    header : {
                        type: Constants.HEADER_GENERAL,
                        imgSrc: data.city.image,
                        location: _locationName.join(', '),
                    },
                    region : {
                        4: {
                            id: data.city.id,
                            name: data.city.name,
                            image: data.city.image,
                            a_issues: parseInt(data.city.announcement_count),
                            l_issues: parseInt(data.city.legislation_count),
                        },
                        3: {
                            id: data.county.id,
                            name: data.county.name,
                            image: data.county.image,
                            a_issues: parseInt(data.county.announcement_count),
                            l_issues: parseInt(data.county.legislation_count),
                        },
                        2: {
                            id: data.state.id,
                            name: data.state.name,
                            image: data.state.image,
                            a_issues: parseInt(data.state.announcement_count),
                            l_issues: parseInt(data.state.legislation_count),
                        },
                    },
                };
                MeoStorage.set_session(_data);
                if (_fyi.page) {
                    if (_fyi.issue_type == Constants.ANNOUNCEMENT_TYPE) {
                        _fn = meo.call.announcement;
                    }
                    else {
                        _fn = meo.call.legislation;
                    }
//                    MeoStorage.set_fyi({});
                }
                _fn();
            }
//            fn();
        };

        MeoData.setProp = function(prop, val) {
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
        };

        MeoData.getProp = function(prop) {
            var data = MeoStorage.get_session();
            var val = eval('data.' + prop);
            if (val) {
                return unescape(val);
            }
            else {
                return '';
            }
        };

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

        var login = function() {
            $.mobile.changePage('#index_city');
        };

        var retrieveData = function() {
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
//console.log('Need to log in.');
            login();
            return false; // ensure processing doesn't continue, since user_id isn't yet available.
        }
//console.log('Don\'t need to log in.');

        var data = MeoStorage.get_session();
        var force = arguments.length == 1 && arguments[0] == 'force' ? true : false;
        if (force || !data || data && (!data.visited || !data.region)) {
console.log('Need to retrieve data.');
            retrieveData();
            return false;
        }
        else {
//console.log('Do not need to retrieve data.');
            return data;
        }
    };

/*
    Utility functions
*/
    var util = {};

    util.monthNames = ['January','February','March',    'April',  'May',     'June',
                       'July',   'August',  'September','October','November','December'];

    util.logout = function() {
        MeoStorage.clear();
        $.mobile.changePage('index.html');
    };

    util.error = function(jqXHR, textStatus, errorThrown) {
        console.log('Error: ' + textStatus);
        console.log(errorThrown);
        console.log(jqXHR);
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

    util.handle_guest_pass = function(guest_pass) {
        // Determine whether the friend already has a user I.
        var _user = MeoStorage.get_user();
        var data = 'code=' + guest_pass + '&meo_user_id=' + (_user && _user.user_id || '');
        var url = Constants.server + Constants.page.fyi;
        $.ajax({
            url: url,
            dataType: 'jsonp',
            data: data,
            success: util.guest_pass_callback,
            error: util.error
        });
    };

    util.guest_pass_callback = function(data) {
console.log('util.guest_pass_callback');
        var _user = MeoStorage.get_user();
        var newPage;
        var issueType;
        if (data.user_id) {
            _user.user_id = data.user_id;
            _user.phone = data.phone;
            MeoStorage.set_user(_user);
            if (data.issue_type == Constants.ANNOUNCEMENT_TYPE) {
                newPage = '/announcement.html?id='+data.issue_id;
                issueType = Constants.ANNOUNCEMENT_TYPE;
            }
            else if (data.issue_type == Constants.LEGISLATION_TYPE) {
                newPage = '/legislation.html?id='+data.issue_id;
                issueType = Constants.LEGISLATION_TYPE;
            }
            var _fyi = {
                page: newPage,
                issue_type: issueType,
                issue_id: data.issue_id,
            };
            MeoStorage.set_fyi(_fyi);
console.log("Target page: " + newPage);
        }
    };

    util.check_user_id = function() {
console.log('util.check_user_id');
        var _user = MeoStorage.get_user();
console.log(_user);
        var data = 'meo_user_id='+ (_user && _user.user_id || '');
console.log(data);
        var url = Constants.server + Constants.page.check_user_id;
        $.ajax({
            url: url,
            dataType: 'jsonp',
            data: data,
            success: util.check_user_id_callback,
            error: util.error
        });
    };

    util.check_user_id_callback = function(data) {
console.log('util.check_user_id_callback');
console.log(data);
        var _user = MeoStorage.get_user();
        if (data.user_id != _user.user_id) {
            // This really shouldn't happen, but just in case it does--local storage erased, user record erased, for instance.
            //console.log('User ID has changed.');
            MeoStorage.clear();
        }
        if (data.user_id) {
            //console.log('User ID: '+data.user_id);
            _user.user_id = data.user_id
            MeoStorage.set_user(_user);
        }
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

    util.checkForGuestPass = function() {
        var url = location.href;
        var pattern = /\/fyi\/([a-z0-9]{10})$/;
        var guest_pass = '';
        var match;
        if (match = pattern.exec(url)) {
            guest_pass = match[1];
        }
console.log('Guest pass: ' + guest_pass);
        return guest_pass;
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
console.log('Check for code.');
console.log(location.href);
        var code = util.checkForGuestPass();
        if (code) {
            util.handle_guest_pass(code);
        }
        else {
            util.check_user_id();
        }
    }

