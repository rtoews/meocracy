/*
    Utility functions
*/
    var util = {};

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
        var data = [];
        for (var i = 0; i < kv.length; i++) {
            var pair = kv[i].split('=');
            data[pair[0]] = pair[1];
        }
        return param ? data[param] : q;
    };

/*
    Object in which to store page-specific data.
*/
    var Page = {}; 

/*
    JSON FUNCTIONS
*/
    var meo = {};
        
    meo.error = function(jqXHR, textStatus, errorThrown) {
        console.log('Error: ' + textStatus);
        console.log(errorThrown);
        console.log(jqXHR);
//        navigator.notification.alert('Error: ' + textStatus);
//        navigator.notification.alert(errorThrown);
//        navigator.notification.alert(jqXHR);
    };
        

/*
    Callback functions for processing JSON data received from server for insertion into HTML
*/
    meo.callback = {

        // Top navigation pages

        index : function(data) {
            var cityPending = util.fmtPending(data.city.issue_count);
            var countyPending = util.fmtPending(data.county.issue_count);
            var statePending = util.fmtPending(data.state.issue_count);
            $('li#city .location').html(data.city.name + '<br/>');
            $('li#city a img').attr('src', data.city.image)
            $('li#city .pending').html(cityPending + '<br/>');
            $('li#county .location').html(data.county.name + '<br/>');
            $('li#county a img').attr('src', data.county.image)
            $('li#county .pending').html(countyPending + '<br/>');
            $('li#state .location').html(data.state.name + '<br/>');
            $('li#state a img').attr('src', data.state.image)
            $('li#state .pending').html(statePending + '<br/>');
            $.mobile.hidePageLoadingMsg();
        },

        search : function() {
        },

        settings : function() {
        },

        alert_manager : function(data) {
            $.mobile.hidePageLoadingMsg();
            Page.alerts = [];
            var id, tag, el;
            for (var i = 0, j = data.tags.length; i < j; i++) {
                id = data.tags[i].tag_id;
                tag = data.tags[i].tag;
                el = '<li><input type="checkbox" name="alerts" value="' + id + '"/>' + tag + '</li>';
                $('#alert_list').append(el);
                Page.alerts[id] = {
                    id: data.tags[i].tag_id,
                    tag: data.tags[i].tag
                };
            }
            $.mobile.hidePageLoadingMsg();
        },

        // List of Announcements / Legislation

        issues : function(data) {
            $('#annleg').append('<li id="announcements" data-theme="e">Announcements</li>');
            for (var i = 0, j = data.announcements.length; i < j; i++) {
                var tmpA = data.announcements[i];
                var link = tmpA.feedback == 1 ? 'announcement_feedback_response.html' : 'announcement.html';
                var el = '';
                el += '<li>';
                el += '    <a href="' + link + '?reg=' + Page.reg + '&id=' + tmpA.id + '">';
                el += '        <img src="' + tmpA.image + '"/>';
                el += '        ' + tmpA.title;
                el += '    </a>';
                el += '</li>';
                $('#annleg').append(el);
            }
            $('#annleg').listview('refresh');
            $.mobile.hidePageLoadingMsg();
        },

        // Announcement data

        announcement : function(data) {
            var calendared = data.calendared;
            var days_remaining = data.days_remaining;
            var description = data.description;
            var image = data.image;
            var status = data.status;
            var vote = data.vote;
            $('#image').attr('src', image);
            $('#description').html(description);
            $('#status').html(data.status);
            $('#calendared').html(calendared);
            $('#vote').html(vote);
            $('#days_remaining').html(days_remaining);
            $.mobile.hidePageLoadingMsg();
        },

        announcement_save : function(data) {
            Page.type = data.type;
            Page.id = data.id;
            $.mobile.changePage('announcement_feedback_response.html');
        },

        announcement_feedback : function(data) {
            var image = data.image;
            var question = data.question;
            var support = data.support;
            var oppose = data.oppose;
            var total = support + oppose;
            $('#image').attr('src', image);
            $('#question').html(question);
            $('#support').html(support);
            $('#oppose').html(oppose);
            $('#total').html(total);
            $.mobile.hidePageLoadingMsg();
        },

        // Data for View Comments page

        view_comments : function(data) {
            $.mobile.hidePageLoadingMsg();
        },

        // Data for Tell Friends page

        tell_friends : function(data) {
            Page.friends = [];
            var id, name, el;
            for (var i = 0, j = data.friends.length; i < j; i++) {
                ndx = data.friends[i].ndx;
                name = data.friends[i].name;
                el = '<li><input type="checkbox" name="friend_ndx" value="' + ndx + '"/>' + name + '</li>';
                $('#friends').append(el);
                Page.friends[ndx] = {
                    name: data.friends[i].name,
                    first: data.friends[i].first,
                    last: data.friends[i].last,
                    email: data.friends[i].email,
                    phone: data.friends[i].phone
                };
            }
            $.mobile.hidePageLoadingMsg();
        },

        friends_message_send : function(data) {
            $.mobile.hidePageLoadingMsg();
        },

        // Data for Alerts page
        alerts : function(data) {
            var el = [], alert;
            for (var i = 0, j = data.alerts.length; i < j; i++) {
                alert = data.alerts[i];
                if (alert['is_set'] == 1) {
                    el = '<li><input type="checkbox" id="alerts" name="alerts[]" value="' + alert['id'] + '" checked/>' + alert['tag'] + '</li>';
                }
                else {
                    el = '<li><input type="checkbox" id="new_alerts" name="new_alerts[]" value="' + alert['id'] + '"/>' + alert['tag'] + '</li>';
                }
                $('#alerts').append(el);
            }
            $('#alerts').listview('refresh');
            $.mobile.hidePageLoadingMsg();
        }


    };


/*
    Functions for requestiong data to be loaded into Meocracy pages
*/

    meo.call = {

        // Top navigation pages

        index : function() {
            $.mobile.showPageLoadingMsg();
            $.ajax({
                url: Constants.server + Constants.page.index,
                dataType: 'jsonp',
                data: '',
                success: meo.callback.index,
                error: meo.error
                });
        },

        search : function() {
        },

        settings : function() {
        },

        alert_manager : function() {
            $.mobile.showPageLoadingMsg();
            var url = Constants.server + Constants.page.alert_manager;
            $.ajax({
                url: url,
                dataType: 'jsonp',
                data: '',
                success: meo.callback.alert_manager,
                error: meo.error
            });
        },

        // Announcement / Legislation list

        issues : function() {
            $.mobile.showPageLoadingMsg();
            var url = Constants.server + Constants.page.issues;
            $.ajax({
                url: url,
                dataType: 'jsonp',
                data: 'reg=' + Page.reg,
                success: meo.callback.issues,
                error: meo.error
            });
        },

        // Announcement details pages

        announcement : function() {
            $.mobile.showPageLoadingMsg();
            var url = Constants.server + Constants.page.announcement;
            $.ajax({
                url: url,
                dataType: 'jsonp',
                data: 'reg=' + Page.reg + '&id=' + Page.id,
                success: meo.callback.announcement,
                error: meo.error
            });
        },

        announcement_save : function(data) {
            console.log('preparing to save... ' + data);
            $.mobile.showPageLoadingMsg();
            var url = Constants.server + Constants.page.announcement_save;
            $.ajax({
                url: url,
                dataType: 'jsonp',
                data: data,
                success: meo.callback.announcement_save,
                error: meo.error
            });
        },

        announcement_feedback : function() {
            console.log(Constants.server + Constants.page.announcement_feedback + '?reg=' + Page.reg + '&id=' + Page.id);
            $.mobile.showPageLoadingMsg();
            var url = Constants.server + Constants.page.announcement_feedback;
            $.ajax({
                url: url,
                dataType: 'jsonp',
                data: 'reg=' + Page.reg + '&id=' + Page.id,
                success: meo.callback.announcement_feedback,
                error: meo.error
            });
        },

        // View Comments page

        view_comments : function() {
            $.mobile.showPageLoadingMsg();
            var url = Constants.server + Constants.page.view_comments;
            $.ajax({
                url: url,
                dataType: 'jsonp',
                data: 'type=' + Page.type + '&id=' + Page.id,
                success: meo.callback.view_comments,
                error: meo.error
            });
        },

        // Tell Friends page

        tell_friends : function() {
            $.mobile.showPageLoadingMsg();
            var url = Constants.server + Constants.page.tell_friends;
            $.ajax({
                url: url,
                dataType: 'jsonp',
                data: 'type=' + Page.type + '&id=' + Page.id,
                success: meo.callback.tell_friends,
                error: meo.error
            });
        },

        friends_message : function() {
            $.mobile.showPageLoadingMsg();
            var contacts = [];
            $('#id').val(Page.id);
            $('#type').val(Page.type);
            var el = $('input[name="friend_ndx"]:checked');
            for (var i = 0, j = el.length; i < j; i++) {
                var ndx = el[i].value;
                var contact_info = Page.friends[ndx].email;
                contacts.push(contact_info);
            }
            $('#contact_list').val(contacts.join('|'));
        },

        friends_message_send : function(data) {
            console.log('Sending message to friends');
            console.log(data);
            $.mobile.showPageLoadingMsg();
            var url = Constants.server + Constants.page.friends_message_send;
            $.ajax({
                url: url,
                dataType: 'jsonp',
                data: data,
                success: meo.callback.friends_message_send,
                error: meo.error
            });
        },

        // Keep apprised page

        alerts : function() {
            $.mobile.showPageLoadingMsg();
            var url = Constants.server + Constants.page.alerts;
            $.ajax({
                url: url,
                dataType: 'jsonp',
                data: 'type=' + Page.type + '&id=' + Page.id,
                success: meo.callback.alerts,
                error: meo.error
            });
        }

    };

/*
    FOR LOADING PAGES.
*/

    // Top navigation pages

    $(document).delegate('#index', 'pageshow', function() {
        console.log('#index pageshow fired');
        meo.call.index();
    });

    $(document).delegate('#search', 'pageshow', function() {
        console.log('#search pageshow fired');
        meo.call.search();
    });

    $(document).delegate('#settings', 'pageshow', function() {
        console.log('#settings pageshow fired');
        meo.call.settings();
    });

    $(document).delegate('#alert_manager', 'pageshow', function() {
        console.log('#alert_manager pageshow fired.');
        meo.call.alert_manager();
    });


    // Announcement / Legislation list

    $(document).delegate('#issues', 'pageshow', function() {
        console.log('#issues pageshow fired.');
        Page.reg = util.queryString('reg');
        console.log('Page.reg=' + Page.reg);
        meo.call.issues();
    });


    // Announcement details pages

    $(document).delegate('#announcement', 'pageshow', function() {
        console.log('#announcement pageshow fired.');
        Page.id = util.queryString('id');
        Page.type = util.queryString('type');
        Page.response = util.queryString('response');
        // if the query string has a response parameter, the announcement for was submitted, and the response must be saved.
        if (Page.response) {
            var data = util.queryString();
            meo.call.announcement_save(data);
        }
        else {
            meo.call.announcement();
        }
    });

    $(document).delegate('#announcement_feedback', 'pageshow', function() {
        console.log('#announcement_feedback pageshow fired.');
        Page.id = Page.id || util.queryString('id');
        Page.reg = Page.reg || util.queryString('reg');
        meo.call.announcement_feedback();
    });

    // View Comments page

    $(document).delegate('#view_comments', 'pageshow', function() {
        console.log('#view_comments pageshow fired.');
        meo.call.view_comments();
    });

    // Tell Friends page

    $(document).delegate('#tell_friends', 'pageshow', function() {
        console.log('#tell_friends pageshow fired.');
        Page.id = util.queryString('id');
        Page.type = util.queryString('type');
        meo.call.tell_friends();
    });

    $(document).delegate('#friends_message', 'pageshow', function() {
        console.log('#friends_message pageshow fired.');
        var message = util.queryString(message);
        if (message) {
            meo.call.friends_message_send(util.queryString());
        }
        else {
            meo.call.friends_message();
        }
    });

    // Keep Apprised page

    $(document).delegate('#alerts', 'pageshow', function() {
        console.log('#alerts pageshow fired.');
        meo.call.alerts();
    });


