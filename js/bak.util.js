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
        get_user_id : function() {
            var user_id = localStorage.getItem('meo_user_id');
            return user_id;
        },

        set_user_id : function(user_id) {
            localStorage.setItem('meo_user_id', user_id);
        },

        get : function(key) {
//            var data = util.getCookie('meopage');
            var data = JSON.parse(localStorage.getItem(key));
            return data;
        },

        set : function(key, data) {
//            util.setCookie('meopage', JSON.stringify(data));
            data = JSON.stringify(data);
            localStorage.setItem(key, data);
        },
    };

    var MeoData = function() {
        var fn = arguments.callee.caller;

        MeoData.callback = function(data) {
            var _sess = { 
                visited : data.visited || [],
                region : {
                    4: {
                        name: data.city.name,
                        image: data.city.image,
                        a_issues: parseInt(data.city.announcement_count),
                        l_issues: parseInt(data.city.legislation_count),
                    },
                    3: {
                        name: data.county.name,
                        image: data.county.image,
                        a_issues: parseInt(data.county.announcement_count),
                        l_issues: parseInt(data.county.legislation_count),
                    },
                    2: {
                        name: data.state.name,
                        image: data.state.image,
                        a_issues: parseInt(data.state.announcement_count),
                        l_issues: parseInt(data.state.legislation_count),
                    },
                },
            };
            MeoStorage.set('meopage', _sess);
            fn();
        };

        MeoData.set = function(data) {
            MeoStorage.set('meopage', data);
        };

        MeoData.setVisited = function(region, type) {
            var _sess = MeoStorage.get('meopage');
            var visited = _sess.visited;
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

        MeoData.forceUpdate = function() {
            retrieveData();
        };

        var login = function() {
//            $.mobile.changePage('login.html');
            $.mobile.changePage('#settings_zip', {transition:'pop', reverse:true, changeHash:false});
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

        var user_id = MeoStorage.get_user_id();
        if (!user_id) {
            login();
            return false; // ensure processing doesn't continue, since user_id isn't yet available.
        }
        // If we're using localStorage, this shouldn't be necessary.
        MeoStorage.set_user_id(user_id);

        var data = MeoStorage.get('meopage');
        if (!data || data && (!data.visited || !data.region)) {
            retrieveData();
            return false;
        }
        else {
            return data;
        }
    };

/*
    Utility functions
*/
    var util = {};

    util.monthNames = ['January','February','March',    'April',  'May',     'June',
                       'July',   'August',  'September','October','November','December'];

    util.check_login = function() {
        var login = false;
//        var user_id = util.getCookie('meouser');
        var user_id = MeoStorage.get_user_id();
        if (user_id) {
            App.user_id = user_id;
            login = true;
        }
        return login;
    };

    util.getCookie = function(name) {
        var value = null;
        var ca = document.cookie.split(';');
        for (var i = 0, j = ca.length; i < j; i++) {
            var c = ca[i];
            var key = $.trim(c.substr(0, c.indexOf('=')));
            if (key == name) {
                value = c.substr(c.indexOf('=')+1);
            }
        }
        return value;
    };

    util.setCookie = function(name, value) {
        var expires = '';
        if (arguments.length == 3) {
            // persistent cookie
            var expdate = new Date();
            expdate.setDate(expdate.getDate() + 180);
            expires = '; expires=' + expdate.toUTCString()
        }
        document.cookie = name + '=' + value + expires;
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
//        var user_id = util.getCookie('meouser');
        var user_id = MeoStorage.get_user_id();
        var App = {
            login_required : user_id == null,
            card : 'meo_user_id=' + user_id,
            user_id : user_id,
        };
    }

