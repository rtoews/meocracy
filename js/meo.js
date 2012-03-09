/*
    JSON FUNCTIONS
*/
    var meo = {};

    meo.makeList = function($pageObj, $pageEl, data, options) {
        var list = '';
        var checkboxes = options.checkboxes ? 1 : 0;
        for (var i = 0, j = data.length; i < j; i++) {
            var key = data[i].key;
            var is_checked = data[i].is_checked;
            var value = data[i].value;
            var image = data[i].image;
            if (checkboxes) {
                var checked = is_checked ? 'checked="checked"' : '';
                list += '<input type="checkbox" ' + checked + ' name="item[]" id="key'+key+'" value="' + key + '" class="custom"/><label for="key' + key + '">' + value + '</label>';
            }
            else {
                var link = is_checked ? options.checked_link : options.unchecked_link;
                var region = options.region;
                list += '<li><a href="' + link + '?reg=' + region + '&id=' + key + '"><img src="' + image + '"/>' + value + '</a></li>';
            }
        }
        $pageEl.html(list);
        $pageObj.trigger('create');
        return list;
    };
        
    meo.pageHeader = function(data) {
console.log('meo.pageHeader');
console.log(data);
        if (data.region) {
            var region = Constants.REGION_CITY;
            $('.header_image').attr('src', data.region[region].image);
            var location = data.region[4].name + ', ' + data.region[3].name + ' County, ' + data.region[2].name;
            $('.header_location').html(location);
        }
        else if (data.sponsors) {
            var sponsor = data.sponsors[0];
            var location_link = 'issues.html?loc=' + data.current_location;
            var img_src = Constants.SPONSOR_IMG_DIR + sponsor.img_name + '.jpg';
            var sponsor_position = data.sponsors.length > 1 ? 'Chair, ' : '';
            sponsor_position += data.location_description + ' Committee';
            $('.header_link').attr('href', location_link);
            $('.header_sponsor_image').attr('src', img_src);
            $('.sponsor_title').html(sponsor.office);
            $('.sponsor_name').html(sponsor.name);
            $('.header_sponsor_position').html(sponsor_position);
            
        }
    };

    meo.error = function(jqXHR, textStatus, errorThrown) {
        console.log('Error: ' + textStatus);
        console.log(errorThrown);
        console.log(jqXHR);
    };

    meo.serverCall = function(url, data, callback) {
        data = App.card + '&' + data;
        $.ajax({
            url: url,
            dataType: 'jsonp',
            data: data,
            success: callback,
            error: meo.error
        });
    };

    meo.validated = function() {
        if (util.check_login()) {
            return true;
        }
    };


/*
    Callback functions for processing JSON data received from server for insertion into HTML
*/
    meo.callback = {

        login_check : function(data) {
            if (data.user_id) {
                App.user_id = data.user_id;
                $.mobile.changePage('index.html');
            } 
            else {
alert('Going to login page from meo.callback.login_check');
                $.mobile.changePage('login.html?invalid');
            }            
        },

        city_list : function(data) {
            var el;
                $('#city_list').html('');
                for (var i = 0, j = data.length; i < j; i++) {
                    var el = '';
                    el += '<li>';
                    el += '    <a data-id="' + data[i].key + '" data-name="' + data[i].value + '" href="#">';
                    el += '        ' + data[i].value;
                    el += '    </a>';
                    el += '</li>';
        
                    $('#city_list').append(el);        
                }
                $('#city_list a').click(meo.call.save_city);
                $('#city_list').listview('refresh');
            $.mobile.hidePageLoadingMsg();
        },

        save_city : function(data) {
            console.log('meo.callback.save_city');
            console.log(data);
            // When a user visits for the first time and selects a city,
            // the server returns the user ID, which should be placed in
            // local storage.
            MeoStorage.set_user_id(data.user_id);
            MeoData.forceUpdate();
            $.mobile.changePage('#index');
        },

        // Top navigation pages

        index : function(data) {
            var Page = {};
            Page.region = {
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
            }
            var pagedata = JSON.stringify(Page);
            MeoStorage.set('meopage', pagedata);
            var cityPending = util.fmtPending(Page.region[4].a_issues+Page.region[4].l_issues);
            var countyPending = util.fmtPending(Page.region[3].a_issues+Page.region[3].l_issues);
            var statePending = util.fmtPending(Page.region[2].a_issues+Page.region[2].l_issues);
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

        homepage : function() {
        },

        search : function(data) {
        },

        // meo.callback.settings
        settings : function(data) {
        },

        alert_manager : function(data) {
            $.mobile.hidePageLoadingMsg();
            meo.makeList($('#alert_manager'), $('#tag_list'), data.tags, { checkboxes : true });
            $.mobile.hidePageLoadingMsg();
        },

        alert_manager_save : function(data) {
            $.mobile.hidePageLoadingMsg();
            if (data.success) {
                $.mobile.changePage('index.html');
            }
        },

        // List of Announcements / Legislation
        legislation_categories : function(data) {
            $.mobile.showPageLoadingMsg();
            $('.list li:gt(1)').remove();
            $('.filter_list li').remove();
            var from;
            var template;
            if (data) {
                from = ['_ID_', '_TITLE_', '_COUNT_'];
                template = 
            		'<li><a href="issues_legislation.html?id=_ID_">' +
            		'<h3>_TITLE_</h3>' +
                    '<span class="comments ui-li-count">_COUNT_</span>' +
                    '</a></li>';
                var issues = data;
                for (var i = 0, j = issues.length; i < j; i++) {
                    var count = issues[i].count;
                    if (!count) continue;
                    var key = issues[i].key;
                    var value = issues[i].value;
                    var to = [key, value, count];
                    var item = template.str_replace(from, to); 
//                    $('#lc_list').append(item);
                    $('.filter_list').append(item);
                }
                $('.filter_list').listview('refresh');
//                $('#lc_list').listview('refresh');
            }
            $.mobile.hidePageLoadingMsg();
        },

        // meo.callback.issues
        issues : function(data) {
console.log('meo.callback.issues');
console.log(data);
            $.mobile.showPageLoadingMsg();
            $('#issue_list li').remove();
            var from;
            var template;
            if (data) {
                // remember the user's category selection.
                if (_sess = MeoData()) {
                    _sess.category = {
                        id : data.category_id,
                        category : data.category,
                    }
                    MeoData.set(_sess);
                }
                $('.issues_category').html(data.category);
                from = ['_LINK_', '_ID_', '_SRC_', '_TITLE_', '_DATE_'];
                template = 
            		'<li><a href="_LINK_.html?id=_ID_" rel="external">' +
            		'<img src="_SRC_">' +
            		'<h3>_TITLE_</h3>' +
            		'<p>_DATE_</p>' +
                    '</a></li>';
                var issues = data.issues;
                var item;
                if (issues.length > 0) {
                    for (var i = 0, j = issues.length; i < j; i++) {
                        var issue = issues[i];
                        var key = issue.key;
                        var type = issue.type;
                        var link = type == '2' ? 'legislation' : 'announcement';
                        var value = issue.value;
                        var image = issue.image || '';
                        var to = [link, key, image, value, 'February 20, 2012'];
                        item = template.str_replace(from, to); 
                        $('#issue_list').append(item);
                    }
                }
                else {
                    item = '<li>Nothing in this category</li>';
                    $('#issue_list').append(item);
                }
                $('#issue_list').listview('refresh');
            }
            $.mobile.hidePageLoadingMsg();
        },

        issue : function(data) {
console.log('meo.callback.issue');
console.log(data);
            var title = data.title;
            var location = data.location;
            var question = data.question;
            var calendared = util.fmtDate(data.calendared);
            var days_remaining = data.days_remaining;
            var description = data.description;
            var image = data.image;
            var status = data.status;
            var vote = util.fmtDate(data.vote);
            $('.issue_title').html(title);
            $('.issue_date').html(calendared);
            $('.issue_location').html(location);
            $('.issue_image').attr('src', image);
            $('.issue_content').html(description);
            $('.issue_question').html(question);
            $.mobile.hidePageLoadingMsg();
        },

        issue_save : function(data) {
            $.mobile.changePage('issue_feedback_response.html');
        },

        issue_feedback : function(data) {
console.log('issue_feedback callback');
            var _sess = MeoData();
            var title = data.title;
            var vote = util.fmtDate(data.vote);

            var image = data.image;
            var question = data.question;
            var support = data.support;
            var oppose = data.oppose;
            var total = support + oppose;
            $('.issue_image').attr('src', image);
            $('.issue_title').html(title);
            $('.issue_date').html(vote);
            $('#support').html(support);
            $('#oppose').html(oppose);
            $('#total').html(total);

            Charts.question = question;
            Charts.support = support;
            Charts.oppose = oppose;
            Charts.votes = total;
            Charts.views = total; // need different number here.
            Charts.drawVotesChart();
            Charts.drawTotalsChart();

            // Sponsors
            $('.issue_feedback_footer li:gt(3)').remove();
        	var template = 
                '<li>' +
        		'   <a href="contact.html?id=_ID_">' +
        		'   <img src="_SRC_">' +
        		'   <h3>_SPONSOR_</h3>' +
        		'   <p>_REGION_</p>' +
        		'   </a>' +
                '</li>';
            var from = ['_ID_', '_SRC_', '_SPONSOR_', '_REGION_'];
            var sponsors = data.sponsors;
            for (var i = 0, j = sponsors.length; i < j; i++) {
                var key = sponsors[i].sponsor_id;
                var image = sponsors[i].public_image;
                var value = sponsors[i].office + ' ' + sponsors[i].full_name;
                var region = sponsors[i].region;
                var to = [key, image, value, region];
                var item = template.str_replace(from, to); 
                $('.issue_feedback_footer').append(item);
            }
            $('.issue_feedback_footer').listview('refresh');
            $('#issue_feedback_response').trigger('create');
            $.mobile.hidePageLoadingMsg();
        },

        // Announcement data

        announcement : function(data) {
            var title = data.title;
            var location = data.location;
            var question = data.question;
            var calendared = util.fmtDate(data.calendared);
            var days_remaining = data.days_remaining;
            var description = data.description;
            var image = data.image;
            var status = data.status;
            var vote = util.fmtDate(data.vote);
            $('.announcement_title').html(title);
            $('.announcement_date').html(calendared);
            $('.announcement_location').html(location);
            $('.announcement_image').attr('src', image);
            $('.announcement_content').html(description);
            $('.announcement_question').html(question);
            $.mobile.hidePageLoadingMsg();
        },

        announcement_save : function(data) {
            $.mobile.changePage('announcement_feedback_response.html');
        },

        announcement_feedback : function(data) {
console.log('announcement_feedback callback');
            var _sess = MeoData();
            var title = data.title;
            var vote = util.fmtDate(data.vote);

            var image = data.image;
            var question = data.question;
            var support = data.support;
            var oppose = data.oppose;
            var total = support + oppose;
            $('.issue_image').attr('src', image);
            $('.issue_title').html(title);
            $('.issue_date').html(vote);
            $('#support').html(support);
            $('#oppose').html(oppose);
            $('#total').html(total);

            Charts.question = question;
            Charts.support = support;
            Charts.oppose = oppose;
            Charts.votes = total;
            Charts.views = total; // need different number here.
            Charts.drawVotesChart();
//            Charts.drawTotalsChart();

            // Sponsors
            $('.announcement_feedback_footer li:gt(3)').remove();
        	var template = 
                '<li>' +
        		'   <a href="contact.html?id=_ID_">' +
        		'   <img src="_SRC_">' +
        		'   <h3>_SPONSOR_</h3>' +
        		'   <p>_REGION_</p>' +
        		'   </a>' +
                '</li>';
            var from = ['_ID_', '_SRC_', '_SPONSOR_', '_REGION_'];
            var sponsors = data.sponsors;
            for (var i = 0, j = sponsors.length; i < j; i++) {
                var key = sponsors[i].sponsor_id;
                var image = sponsors[i].public_image;
                var value = sponsors[i].office + ' ' + sponsors[i].full_name;
                var region = sponsors[i].region;
                var to = [key, image, value, region];
                var item = template.str_replace(from, to); 
                $('.announcement_feedback_footer').append(item);
            }
            $('.announcement_feedback_footer').listview('refresh');
            $('#announcement_feedback_response').trigger('create');
            $.mobile.hidePageLoadingMsg();
        },

        // Legislation data

        legislation : function(data) {
            meo.pageHeader(data); 
            var bill_id = data.bill_id;
            var sponsor = data.sponsors[0];
            var title = data.title;
            var description = data.description;
            var status = data.status;
            var summary = data.summary;
            $('.legislation_bill_id').html(bill_id);
            $('.legislation_title').html(title);
            $('.legislation_location>span').after(data.current_location);
            $('.legislation_chamber>span').after(data.current_chamber);
            $('.legislation_status').html(data.status);
            $('.legislation_content').html(summary);
            $.mobile.hidePageLoadingMsg();
        },

        legislation_save : function(data) {
console.log('meo.callback.legislation_save');
console.log(data);
            if (data.feedback_id) {
                $('#legislation_feedback_id').val(data.feedback_id);
                $.mobile.changePage('#legislation_feedback');
            }
            else {
                $.mobile.changePage('legislation_feedback_response.html');
            }
        },

        legislation_feedback : function(data) {
console.log(data);
            var _sess = MeoData();
            var title = data.title;
            var vote = util.fmtDate(data.vote);

            var question = data.question;
            var support = data.support;
            var oppose = data.oppose;
            var total = support + oppose;
            $('.issue_title').html(title);
            $('.issue_date').html(vote);
            $('#support').html(support);
            $('#oppose').html(oppose);
            $('#total').html(total);

            Charts.question = question;
            Charts.support = support;
            Charts.oppose = oppose;
            Charts.votes = total;
            Charts.views = total; // need different number here.
//            Charts.drawVotesChart();
//            Charts.drawTotalsChart();
            // Sponsors
            $('.legislation_feedback_footer li:gt(3)').remove();
        	var template = 
                '<li>' +
        		'   <a href="contact.html?id=_ID_">' +
        		'   <img src="_SRC_">' +
        		'   <h3>_SPONSOR_</h3>' +
        		'   <p>_OFFICE_ (_PARTY_)</p>' +
        		'   </a>' +
                '</li>';
            var from = ['_ID_', '_SRC_', '_SPONSOR_', '_OFFICE_', '_PARTY_'];
            var sponsors = data.sponsors;
            for (var i = 0, j = sponsors.length; i < j; i++) {
                var key = sponsors[i].id;
                var image = '';
                if (sponsors[i].img_name) {
                    image = Constants.SPONSOR_IMG_DIR + sponsors[i].img_name + '.jpg'; // safety net for "undefined" images
                } 
                var full_name = sponsors[i].full_name;
                var office = sponsors[i].office;
                var region = sponsors[i].region;
                var party = sponsors[i].party;
                var to = [key, image, full_name, office, party];
                var item = template.str_replace(from, to); 
                $('.legislation_feedback_footer').append(item);
            }
            $('.legislation_feedback_footer').listview('refresh');
            $('#legislation_feedback_response').trigger('create');
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
            var el = [], tag;
            if (data.alerts) {
                meo.makeList($('#alerts'), $('#tag_list'), data.alerts, { checkboxes : true });
                $.mobile.hidePageLoadingMsg();
            }
            else if (data.success) {
                var _sess = Session.get();
                var next = _sess.issue.type == Constants.LEGISLATION_TYPE ? 'legislation_feedback_response.html' : 'announcement_feedback_response.html';
                $.mobile.changePage(next);
            }
        }


    };


/*
    Functions for requestiong data to be loaded into Meocracy pages
*/

    meo.call = {

        login_check : function() {
console.log('meo.call.login_check');
            var url = Constants.server + Constants.page.login_check;
            var data = 'm=' + util.queryString('mobile_phone') + '&p=' + util.queryString('password');
            meo.serverCall(url, data, meo.callback.login_check);
        },

        settings_zip : function() {
            if (arguments.length == 0) {
                Geo.getZip.getLocation();
            }
            else {
                var geo_zip = arguments[0];
                $('#place_zip').val(geo_zip)
                    .keyup(function() {
                        var zip = $('#place_zip').val();
                        if (zip.length == 5) {
                            meo.call.settings_zip(zip);
                        }
                    });
                if (geo_zip.length == 5) {
                    $.mobile.showPageLoadingMsg();
                    var url = Constants.server + Constants.popup.city_select;
                    var data = 'zip=' + geo_zip;
                    meo.serverCall(url, data, meo.callback.city_list);
                }
            }
        },

        save_city : function() {
console.log('meo.call.save_city ' + $(this).attr('data-id') + '; ' + $(this).attr('data-name'));
            var city_id = $(this).attr('data-id');
            var url = Constants.server + Constants.process.save_city;
            var data = 'city_id=' + city_id;
            meo.serverCall(url, data, meo.callback.save_city);
        },

        // Top navigation pages

        index : function() {
console.log('meo.call.index');
            var _sess;
            $.mobile.showPageLoadingMsg();
            if (_sess = MeoData()) {
                meo.pageHeader(_sess);
            }
            $.mobile.hidePageLoadingMsg();
        },

        homepage : function() {
            var _sess;
            if (_sess = MeoData()) {
                var _reg = util.queryString('reg') || _sess.current_region.key || 4; // choose by query string or cookie or 4
                if (_reg == 2) {
                    var _reg_label = 'State of ' + _sess.region[_reg].name;
                    var _reg_label_about = _sess.region[_reg].name;
                }
                else if (_reg == 3) {
                    var _reg_label = _sess.region[_reg].name + ' County';
                    var _reg_label_about = _reg_label;
                }
                else if (_reg == 4) {
                    var _reg_label = 'City of ' + _sess.region[_reg].name;
                    var _reg_label_about = _sess.region[_reg].name;
                }
                _sess.current_region = {
                    key: _reg,
                    label: _reg_label,
                    label_about: _reg_label_about
                };
                MeoData.set(_sess);
                meo.pageHeader(_sess); 

                $('#region_label').html(_reg_label);            
                $('#region_label_about').html(_reg_label_about);
                $('#homepage_announcement_link')
                    .attr('data-theme', _sess.visited[_reg] && _sess.visited[_reg] & Constants.ANNOUNCEMENT_TYPE ? 'e' : 'a')
                    .find('.comments').html(util.fmtPending(_sess.region[_reg].a_issues));
                $('#homepage_legislation_link')
                    .attr('data-theme', _sess.visited[_reg] && _sess.visited[_reg] & Constants.LEGISLATION_TYPE ? 'e' : 'a')
                    .find('.comments').html(util.fmtPending(_sess.region[_reg].l_issues));
            }
        },

        // meo.call.search
        search : function(data) {
            var search = util.queryString('search');
            // Most of this doesn't get processed unless a search value has been entered.
            if (search) {
                var url = Constants.server + Constants.page.issues;
                var data = 'search=' + search;
                meo.serverCall(url, data, meo.callback.issues);
                $.mobile.changePage('issues.html');
            }
        },

        settings : function() {
            var url = Constants.server + Constants.page.settings;
            var data = 'mode=1';
            meo.serverCall(url, data, meo.callback.settings);
        },

        about : function() {
            var _sess;
            if (_sess = MeoData()) {
                meo.pageHeader(_sess); 
                $('.region_heading_about').html(_sess.current_region.label_about);
            }
        },

        alert_manager : function() {
            $.mobile.showPageLoadingMsg();
            var url = Constants.server + Constants.page.alert_manager;
            var data = 'mode=1';
            meo.serverCall(url, data, meo.callback.alert_manager);
        },

        alert_manager_save : function(data) {
            $.mobile.showPageLoadingMsg();
            var url = Constants.server + Constants.page.alert_manager;
            var data = 'mode=2&' + data;
            meo.serverCall(url, data, meo.callback_alert_manager_save);
        },


        // Announcement / Legislation list

        // meo.call.issues
        issues : function(type) {
            var _sess;
            if (_sess = MeoData()) {
console.log('meo.call.issues');
console.log(_sess);
                var _category = util.queryString('id') || _sess.category.id;
                var _location = util.queryString('loc');
                meo.pageHeader(_sess); 
                _sess.category = {
                    id : _category,
                }
                MeoData.set(_sess);

                var data;
                var url = Constants.server + Constants.page.issues;
                if (_location) {
                    data = 'loc=' + _location;
                }
                else {
                    data = 'cat=' + _category;
                }
                meo.serverCall(url, data, meo.callback.issues);
console.log(url);
console.log(data);
            }
        },

        issue : function() {
console.log('meo.call.issue');
            $.mobile.showPageLoadingMsg();
            var _sess;
            if (_sess = MeoData()) {
                var id = util.queryString('id');
                var type = util.queryString('t');
                var url = Constants.server + Constants.page.issue;
                meo.pageHeader(_sess); 
                _sess.issue = {
                    id : id,
                    type : type,
                };
                MeoData.set(_sess);
                var supportData = {
                    user_id : App.user_id,
                    id : _sess.issue.id,
                    type : _sess.issue.type,
                    response : 1,
                };
                var opposeData = {
                    user_id : App.user_id,
                    id : _sess.issue.id,
                    type : _sess.issue.type,
                    response : -1,
                };
                $('.button_green').click(function() { meo.call.issue_save(supportData); });
                $('.button_red').click(function() { meo.call.issue_save(opposeData); });
console.log('about to summon AJAX');
                var data = 'id=' + id + '&t=' + type;
                meo.serverCall(url, data, meo.callback.issue);
            }
        },

        issue_save : function(data) {
console.log('meo.call.issue_save');
            $.mobile.showPageLoadingMsg();
            var url = Constants.server + Constants.page.issue_save;
            meo.serverCall(url, data, meo.callback.issue_save);
        },

        issue_feedback : function() {
console.log('issue_feedback');
            $.mobile.showPageLoadingMsg();
            var _sess;
            if (_sess = MeoData()) {
                if (!_sess.issue) {
                    $.mobile.changePage('issues.html');
                    return false;
                }
                var id = util.queryString('id') || _sess.issue.id;
                _sess.issue = {
                    id: id,
                };
                MeoData.set(_sess);
                var url = Constants.server + Constants.page.issue_feedback;
                var data = 'id=' + id;
                meo.serverCall(url, data, meo.callback.issue_feedback);
            }
        },

        // Announcement details pages
        // meo.call.announcement
        announcement : function() {
            $.mobile.showPageLoadingMsg();
            var _sess;
            if (_sess = MeoData()) {
                var id = util.queryString('id');
                var url = Constants.server + Constants.page.announcement;
                meo.pageHeader(_sess); 
                $('.category_name').html(_sess.category.category);
                $('a.category_name').attr('href', 'issues.html?id=' + _sess.category.id);
//                $('.announcement_header').html(_sess.current_region.label + ' Announcement');
                _sess.issue = {
                    id : id,
                    type : Constants.ANNOUNCEMENT_TYPE,
                };
//                _sess.visited = MeoData.setVisited(_sess.current_region.key, Constants.ANNOUNCEMENT_TYPE);
                MeoData.set(_sess);
                var supportData = {
                    user_id : App.user_id,
                    id : _sess.issue.id,
                    type : _sess.issue.type,
                    response : 1,
                };
                var opposeData = {
                    user_id : App.user_id,
                    id : _sess.issue.id,
                    type : _sess.issue.type,
                    response : -1,
                };
                $('.button_green').click(function() { meo.call.announcement_save(supportData); });
                $('.button_red').click(function() { meo.call.announcement_save(opposeData); });
                var data = 'id=' + id;
                meo.serverCall(url, data, meo.callback.announcement); 
            }
        },

        announcement_save : function(data) {
console.log('announcement_save');
            $.mobile.showPageLoadingMsg();
            var url = Constants.server + Constants.page.announcement_save;
            var callback = data.type == Constants.ANNOUNCEMENT_TYPE ? meo.callback.announcement_save : meo.callback.legislation_save;
console.log(callback);
            meo.serverCall(url, data, callback);
        },

        announcement_feedback : function() {
console.log('announcement_feedback');
            $.mobile.showPageLoadingMsg();
            var _sess;
            if (_sess = MeoData()) {
                if (!_sess.issue) {
                    $.mobile.changePage('issues_announcements.html');
                    return false;
                }
                var id = util.queryString('id') || _sess.issue.id;
                _sess.issue = {
                    id: id,
                    type: Constants.ANNOUNCEMENT_TYPE,
                };
                MeoData.set(_sess);
                var url = Constants.server + Constants.page.announcement_feedback;
                var data = 'id=' + id;
                meo.serverCall(url, data, meo.callback.announcement_feedback);
            }
        },

        // Legislation details pages

        legislation : function() {
            $.mobile.showPageLoadingMsg();
            var _sess;
            if (_sess = MeoData()) {
                var id = util.queryString('id');
                var url = Constants.server + Constants.page.legislation;
                $('.category_name').html(_sess.category.category);
                $('a.category_name').attr('href', 'issues.html?id=' + _sess.category.id);
                _sess.issue = {
                    id : id,
                    type : Constants.LEGISLATION_TYPE,
                };
                MeoData.set(_sess);
                $('.button_green').click(function() { meo.call.legislation_save(1); });
                $('.button_red').click(function() { meo.call.legislation_save(-1); });
                var data = 'id=' + id;
                meo.serverCall(url, data, meo.callback.legislation);
            }
            else {
                alert("appear to have not gotten _sess data");
            }
        },

        legislation_feedback : function() {
            $.mobile.showPageLoadingMsg();
            $('#legislation_comments').val('');
            var _sess;
            if (_sess = MeoData()) {
                meo.pageHeader(_sess); 
                $('.category_name').html(_sess.category.category);
                $('a.category_name').attr('href', 'issues.html?id=' + _sess.category.id);
                if (!_sess.issue) {
                    $.mobile.changePage('issues_legislation.html');
                    return false;
                }
                var id = util.queryString('id') || _sess.issue.id;
                _sess.issue = {
                    id: id,
                    type: Constants.LEGISLATION_TYPE
                };
                MeoData.set(_sess);
                var url = Constants.server + Constants.page.legislation_feedback;
                var data = 'id=' + id;
                meo.serverCall(url, data, meo.callback.legislation_feedback);
            }
        },

        legislation_save : function(response) {
console.log('meo.call.legislation_save');
console.log('Response: ' + escape(response));
            if (!meo.validated()) {
                $.mobile.changePage($('#login'), { transition:'pop', reverse:true, changeHash:false });
            }
            else {
                var _sess;
                var data;
                if (_sess = MeoData()) {
                    $.mobile.showPageLoadingMsg();
                    var url = Constants.server + Constants.page.legislation_save;
                    if (typeof response == 'number') {
                        data = 'id='+_sess.issue.id+'&response='+response;
console.log(data);
                    }
                    else {
                        var feedback_id = 0;
                        if (arguments.length == 2) {
                            feedback_id = arguments[1];
                        }
                        data = 'id='+_sess.issue.id+'&fid='+feedback_id+'&response_comments='+escape(response);
console.log(data);
                    }
                    meo.serverCall(url, data, meo.callback.legislation_save);
                }
            }
        },

        feedback_comments : function() {
            var _sess;
            var fid;
            var response;
            if (_sess = MeoData()) {
console.log('meo.call.feedback_comments');
console.log(_sess);
                if (_sess.issue.type == Constants.ANNOUNCEMENT_TYPE) {
                    fid = $('#announcement_feedback_id').val();
                    response = $('#announcement_comments').val();
                    $('#btn_response_send').click(function() { meo.call.announcement_save(response, fid); });
                    $('#btn_response_cancel').click(function() { 
                        $.mobile.changePage('announcement_feedback_response.html');
                    });
                }
                else {
                    fid = $('#legislation_feedback_id').val();
                    $('#legislation_submit').click(function() { 
                        var response = $('#legislation_comments').val();
                        console.log('Comments: ' + response);
                        meo.call.legislation_save(response, fid); 
                    });
                    $('#legislation_cancel').click(function() { 
                        $.mobile.changePage('legislation_feedback_response.html');
                    });
                }
            }
        },

        // View Comments page

        view_comments : function() {
            $.mobile.showPageLoadingMsg();
            var _sess;
            if (_sess = MeoData()) {
                var url = Constants.server + Constants.page.view_comments;
                var data = 'type=' + _sess.issue.type + '&id=' + _sess.issue.id;
                meo.serverCall(url, data, meo.callback.view_comments);
            }
        },

        // Tell Friends page

        tell_friends : function() {
            $.mobile.showPageLoadingMsg();
            var _sess;
            if (_sess = MeoData()) {
                var url = Constants.server + Constants.page.tell_friends;
                var data = 't=' + _sess.issue.type + '&id=' + _sess.issue.id;
                meo.serverCall(url, data, meo.callback.tell_frields);
            }
        },

        friends_message : function(friends) {
            $.mobile.showPageLoadingMsg();
            var _sess;
            if (_sess = MeoData()) {
                var contacts = [];
                $('#id').val(_sess.issue.id);
                $('#type').val(_sess.issue.type);
                var el = $('input[name="friend_ndx"]:checked');
                for (var i = 0, j = el.length; i < j; i++) {
                    var ndx = el[i].value;
                    var contact_info = friends[ndx].email;
                    contacts.push(contact_info);
                }
                $('#contact_list').val(contacts.join('|'));
            }
        },

        friends_message_send : function(data) {
            $.mobile.showPageLoadingMsg();
            var url = Constants.server + Constants.page.friends_message_send;
            meo.serverCall(url, data, meo.callback.friends_message_send);
        },

        // Keep apprised page

        alerts : function(submitted_form) {
            console.log('reached meo.call.alerts');
            $.mobile.showPageLoadingMsg();
            var _sess;
            if (_sess = MeoData()) {
                if (!_sess.issue) {
                    $.mobile.changePage('homepage.html');
                    return false;
                }
                var id = _sess.issue.id;
                var type = _sess.issue.type;
                var url = Constants.server + Constants.page.alerts;
                var mode = submitted_form ? 2 : 1;
                var data = 'mode='+mode+'&type='+type+'&id='+id;
                if (mode == 2) {
                    data += '&'+util.queryString();
                }
                meo.serverCall(url, data, meo.callback.alerts);
            }
        }

    };

/*
    FOR LOADING PAGES.
*/

    // Top navigation pages

    $(document).delegate('#login', 'pagebeforeshow', function(e, obj) {
    });

    $(document).delegate('#login_check', 'pagebeforeshow', function(e, obj) {
        meo.call.login_check();
    });

    $(document).delegate('#index', 'pageshow', function() {
        console.log('#index pageshow fired');
        meo.call.index();
    });

    $(document).delegate('#settings_zip', 'pageshow', function() {
        console.log('pageshow #settings_zip fired');
        meo.call.settings_zip();
    });

    $(document).delegate('#homepage', 'pageshow', function() {
        console.log('#homepage pageshow fired.');
        meo.call.homepage();
//        $.mobile.changePage($('#signup'), {transition: 'pop', reverse: true, changeHash: false });
    });

    $(document).delegate('#search', 'pageshow', function() {
        meo.call.search();
    });

    $(document).delegate('#settings', 'pageshow', function() {
        meo.call.settings();
    });

    $(document).delegate('#about', 'pageshow', function() {
        meo.call.about();
    });

    $(document).delegate('#alert_manager', 'pageshow', function() {
        var alerts = util.queryString('item[]');
        if (alerts) {
            q = util.queryString();
            meo.call.alert_manager_save(q);
        }
        else {
            meo.call.alert_manager();
        }
    });

    $(document).delegate('#alert_manager_save', 'pageinit', function() {
    });


    // Announcement / Legislation list

    $(document).delegate('#issues', 'pageshow', function() {
        $.mobile.showPageLoadingMsg();
        meo.call.issues();
    });

    // Announcement details pages

    $(document).delegate('#announcement', 'pageshow', function() {
        meo.call.announcement();
    });

    $(document).delegate('#announcement_feedback_response', 'pageshow', function() {
        meo.call.announcement_feedback();
    });

    // Legislation details pages

    $(document).delegate('#legislation', 'pageshow', function() {
        meo.call.legislation();
    });

    $(document).delegate('#legislation_feedback_response', 'pageshow', function() {
        meo.call.legislation_feedback();
    });

/*
    $(document).delegate('#issue', 'pageshow', function() {
        $.mobile.showPageLoadingMsg();
        meo.call.issue();
    });

    $(document).delegate('#issue_feedback_response', 'pageshow', function() {
        meo.call.issue_feedback();
    });
*/


    $(document).delegate('#legislation_feedback', 'pageshow', function() {
        meo.call.feedback_comments();
    });
    // View Comments page

    $(document).delegate('#view_comments', 'pageshow', function() {
        meo.call.view_comments();
    });

    // Tell Friends page

    $(document).delegate('#tell_friends', 'pageshow', function() {
        Page.id = util.queryString('id');
        Page.type = util.queryString('type');
        meo.call.tell_friends();
    });

    $(document).delegate('#friends_message', 'pageshow', function() {
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
        console.log('pageshow #alerts fired');
        var form_submit = util.queryString('alerts_save') ? true : false;
        console.log('form_submit: ' + form_submit);
        meo.call.alerts(form_submit);
    });

