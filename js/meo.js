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
                var key_param = options.key_param || 'id';
                var params = [key_param+'='+key];
                var link = is_checked ? options.checked_link : options.unchecked_link;
                if (options.region) {
                    params.push('reg='+options.region);
                }
                var imgTag = image ? '<img src="' + image + '"/>' : '';
                list += '<li><a href="' + link + '?' + params.join('&') + '">' + imgTag + value + '</a></li>';
            }
        }
        $pageEl.html(list);
        $pageObj.trigger('create');
        return list;
    };
        
    meo.buildCityList = function(cities) {
        var li = [];
        for (var i = 0, j = cities.length; i < j; i++) {
            var el = '';
            el += '<li>';
            el += '    <a data-id="' + cities[i].key + '" data-name="' + cities[i].value + '" href="#">';
            el += '        ' + cities[i].value;
            el += '    </a>';
            el += '</li>';
            li.push(el);
        }
        return li.join('');
    };

    meo.setGeneralHeaderProps = function() {
console.log('meo.setGeneralHeadingProps');
        var _mymeo = util.getSessionData();
        var _city = [
            _mymeo.region && _mymeo.region[Constants.REGION_CITY] && _mymeo.region[Constants.REGION_CITY].name || '',
        ];
        var _countyState = [
            _mymeo.region && _mymeo.region[Constants.REGION_COUNTY] && _mymeo.region[Constants.REGION_COUNTY].name ? _mymeo.region[Constants.REGION_COUNTY].name + ' County' : '',
            _mymeo.region && _mymeo.region[Constants.REGION_STATE] && _mymeo.region[Constants.REGION_STATE].name || '',
        ];
        MeoData.setProp('header.type', Constants.HEADER_GENERAL);
        MeoData.setProp('header.citySeal', _mymeo.region && _mymeo.region[Constants.REGION_CITY].image || '');
        MeoData.setProp('header.city', _mymeo.region[Constants.REGION_CITY].name);
        MeoData.setProp('header.county_state', _countyState.join(', '));
    };

    meo.setAnnouncementHeaderProps = function(data) {
console.log('meo.setAnnouncementHeaderProps');
        var _mymeo = util.getSessionData();
        var _city = [
            _mymeo.region && _mymeo.region[Constants.REGION_CITY] && _mymeo.region[Constants.REGION_CITY].name || '',
        ];
        var _countyState = [
            _mymeo.region && _mymeo.region[Constants.REGION_COUNTY] && _mymeo.region[Constants.REGION_COUNTY].name ? _mymeo.region[Constants.REGION_COUNTY].name + ' County' : '',
            _mymeo.region && _mymeo.region[Constants.REGION_STATE] && _mymeo.region[Constants.REGION_STATE].name || '',
        ];
        var _sponsor = {
            imgSrc: data.sponsors && data.sponsors[0] && data.sponsors[0].img_name ? data.sponsors[0].img_name : '',
            name: data.sponsors && data.sponsors[0] && data.sponsors[0].name || '',
            position: data.sponsors && data.sponsors[0] && data.sponsors[0].title ? data.sponsors[0].title : '',
        };
        var _cat = {
            link: 'issues.html?id=' + _mymeo.issue.category_id,
            category_name: _mymeo.issue.category_name,
        };
        MeoData.setProp('header.type', Constants.HEADER_ANNOUNCEMENT);
        MeoData.setProp('header.citySeal', _mymeo.region && _mymeo.region[Constants.REGION_CITY].image || '');
        MeoData.setProp('header.city', _city.join(', '));
        MeoData.setProp('header.county_state', _countyState.join(', '));
        MeoData.setProp('header.issue_link', 'issues.html');
        MeoData.setProp('header.issue_image', data.image);
        MeoData.setProp('header.issue_name', data.title);
        MeoData.setProp('header.issue_data', '');
        MeoData.setProp('header.sponsor.image', _sponsor.imgSrc);
        MeoData.setProp('header.sponsor.name', _sponsor.name);
        MeoData.setProp('header.sponsor.data', _sponsor.position);
        MeoData.setProp('header.cat.name', _cat.category_name);
        MeoData.setProp('header.cat.link', _cat.link);

        MeoData.setProp('issue.type', Constants.HEADER_ANNOUNCEMENT);
        MeoData.setProp('issue.id', data.id);
        MeoData.setProp('issue.image', data.image);
        MeoData.setProp('issue.title', data.title);
        MeoData.setProp('issue.data', '');
        MeoData.setProp('issue.bill_id', '');
        MeoData.setProp('issue.state', '');
        MeoData.setProp('issue.sponsor.link', '');
        MeoData.setProp('issue.sponsor.name', _sponsor.name);
        MeoData.setProp('issue.sponsor.image', _sponsor.imgSrc);
        MeoData.setProp('issue.sponsor.title', _sponsor.position);
        MeoData.setProp('issue.sponsor.data', '');
        MeoData.setProp('announcement.link', 'announcement.html?id=' + data.id);
        MeoData.setProp('announcement.image', data.image);
        MeoData.setProp('announcement.title', data.title);
        MeoData.setProp('announcement.data', '');
    };

    meo.setLegislationHeaderProps = function(data) {
console.log('meo.setLegislationHeaderProps');
        var _mymeo = util.getSessionData();
        var _countyState = [
            _mymeo.region[Constants.REGION_COUNTY].name ? _mymeo.region[Constants.REGION_COUNTY].name + ' County' : '',
            _mymeo.region[Constants.REGION_STATE].name || '',
        ];
        var _sponsor = {
            name: data.sponsors && data.sponsors[0] && data.sponsors[0].name || '',
            fullTitle: data.sponsors && data.sponsors[0] && data.sponsors[0].full_title || '',
            imgSrc: data.sponsors && data.sponsors[0] && data.sponsors[0].img_name ? Constants.SPONSOR_IMG_DIR + data.sponsors[0].img_name + '.jpg' : '',
            title: data.sponsors && data.sponsors[0] && data.sponsors[0].title || '',
        };
        var _senAsm = {
            imgSrc: Constants.LOGO_IMG_DIR + ('state_' + _mymeo.region[Constants.REGION_STATE].name.substr(0,2) + '_' + data.current_chamber).toLowerCase() + '.png',
            billName: data.bill_id + ': ' + data.title,
            billSenAsm: _mymeo.region[Constants.REGION_STATE].name + ' State ' + data.current_chamber,
            link: 'issues.html?loc=' + data.current_location,
        };
        var _cat = {
            link: 'issues.html?id=' + _mymeo.issue.category_id,
            category_name: _mymeo.issue.category_name,
        };
        MeoData.setProp('header.type', Constants.HEADER_LEGISLATION);
        MeoData.setProp('header.citySeal', _mymeo.region[Constants.REGION_CITY].image || '');
        MeoData.setProp('header.city', _mymeo.region[Constants.REGION_CITY].name);
        MeoData.setProp('header.county_state', _countyState.join(', '));
        MeoData.setProp('header.issue_link', _senAsm.link);
        MeoData.setProp('header.issue_image', _senAsm.imgSrc);
        MeoData.setProp('header.issue_name', data.title);
        MeoData.setProp('header.issue_location', _senAsm.billSenAsm);
        MeoData.setProp('header.sponsor.image', _sponsor.imgSrc);
        MeoData.setProp('header.sponsor.name', _sponsor.name);
        MeoData.setProp('header.sponsor.fullTitle', _sponsor.fullTitle);
        MeoData.setProp('header.sponsor.data', _sponsor.title);
        MeoData.setProp('header.cat.name', _cat.category_name);
        MeoData.setProp('header.cat.link', _cat.link);

        MeoData.setProp('issue.type', Constants.HEADER_LEGISLATION);
        MeoData.setProp('issue.id', data.id);
        MeoData.setProp('issue.image', _senAsm.imgSrc);
        MeoData.setProp('issue.title', data.title);
        MeoData.setProp('issue.data', _senAsm.billSenAsm);
        MeoData.setProp('issue.bill_id', data.bill_id);
        MeoData.setProp('issue.state', _mymeo.region[Constants.REGION_STATE].name);
        MeoData.setProp('issue.sponsor.link', '');
        MeoData.setProp('issue.sponsor.name', _sponsor.name);
        MeoData.setProp('issue.sponsor.image', _sponsor.imgSrc);
        MeoData.setProp('issue.sponsor.title', _sponsor.fullTitle);
        MeoData.setProp('issue.sponsor.data', _sponsor.title);
        MeoData.setProp('legislation.link', 'legislation.html?id=' + data.id);
        MeoData.setProp('legislation.image', _senAsm.imgSrc);
        MeoData.setProp('legislation.title', data.title);
        MeoData.setProp('legislation.bill_id', data.bill_id);
        MeoData.setProp('legislation.data', _senAsm.billSenAsm);
    };

    meo.pageHeader = function() {
console.log('meo.pageHeader');
        var _mymeo = util.getSessionData();
        if (_mymeo) {
console.log(_mymeo);
            var _headerType = _mymeo.header && _mymeo.header.type || Constants.HEADER_GENERAL;
            switch (_headerType) {
                case Constants.HEADER_GENERAL:
                    $('.header_image').attr('src', _mymeo.header.imgSrc);
                    $('.header_city_seal').attr('src', _mymeo.header.imgSrc);
                    $('.header_city').html(_mymeo.header.city);
                    $('.header_county_state').html(_mymeo.header.county_state);
                    break;
                case Constants.HEADER_ANNOUNCEMENT:
                    $('.header_city_seal').attr('src', _mymeo.header.citySeal);
                    $('.header_city').html(_mymeo.header.city);
                    $('.header_county_state').html(_mymeo.header.county_state);
                    $('.header_issue_link').attr('href', _mymeo.header.link);
                    $('.header_issue_image').attr('src', _mymeo.header.issue_image);
                    $('.header_issue_name').html(_mymeo.header.issue_name);
                    $('.header_issue_data').html('');
                    $('.header_sponsor_image').attr('src', _mymeo.header.sponsor.image);
                    $('.header_sponsor_name').html(_mymeo.header.sponsor.name);
                    $('.header_sponsor_data').html(_mymeo.header.sponsor.data);
                    $('a.category_name').html(_mymeo.header.cat.name);
                    $('a.category_name').attr('href', _mymeo.header.cat.link);
                    break;
                case Constants.HEADER_LEGISLATION:
                    $('.header_city_seal').attr('src', _mymeo.header.citySeal);
                    $('.header_city').html(_mymeo.header.city);
                    $('.header_county_state').html(_mymeo.header.county_state);
                    $('.header_issue_link').attr('href', _mymeo.header.issue_link);
                    $('.header_issue_image').attr('src', _mymeo.header.issue_image);
                    $('.header_issue_name').html(_mymeo.header.issue_name);
                    $('.header_issue_data').html(_mymeo.header.issue_location);
                    $('.header_sponsor_image').attr('src', _mymeo.header.sponsor.image);
                    $('.header_sponsor_name').html(_mymeo.header.sponsor.name);
                    $('.header_sponsor_data').html(_mymeo.header.sponsor.data);
                    $('a.category_name').html(_mymeo.issue.category_name);
                    $('a.category_name').attr('href', _mymeo.header.cat.link);
                    break;
                default:
                    break;
            }
        }
    };


    meo.announcementFeedbackProvided = function(data) {
console.log('meo.announcementFeedbackProvided');
        var feedback = data.response == -1 ? 'opposition' : 'support';
        MeoData.setProp('issue.feedback_id', data.feedback_id);
        $('#announcement .button_plate').hide();
        $('#announcement .feedback_plate').show().find('.feedback_word').html(feedback);

    };

    meo.legislationFeedbackProvided = function(data) {
console.log('meo.legislationFeedbackProvided');
        var feedback = data.response == -1 ? 'opposition' : 'support';
        MeoData.setProp('issue.feedback_id', data.feedback_id);
        $('#legislation .button_plate').hide();
        $('#legislation .feedback_plate').show().find('.feedback_word').html(feedback);

    };

    meo.announcementComments = function(data) {
console.log('meo.announcementComments');
console.log(data);
        var comments = data.comments;
        var data_theme;
        var disable_comments = false;
        $('#announcement_comment_count').html(data.count);
        $('#announcement_comment_list li').remove();
        for (var i = 0, j = comments.length; i < j; i++) {
            data_theme = 'a';
            if (comments[i].user_id == App.user_id) {
                data_theme = 'e';
                disable_comments = true;
            }
            var li = '<li data-theme="'+data_theme+'"><h4>' + comments[i].user_handle + '</h4><p>' + comments[i].comments + '</p></li>';
            $('#announcement_comment_list').append(li);
        }
        $('#announcement_comment_list').listview('refresh');
        if (disable_comments) {
            $('#announcement_comment_form').slideUp();
        }
    };

    meo.legislationComments = function(data) {
console.log('meo.legislationComments');
console.log(data);
        var comments = data.comments;
        var data_theme;
        var disable_comments = false;
        $('#legislation_comment_count').html(data.count);
        $('#legislation_comment_list li').remove();
        for (var i = 0, j = comments.length; i < j; i++) {
            data_theme = 'a';
            if (comments[i].user_id == App.user_id) {
                data_theme = 'e';
                disable_comments = true;
            }
            var li = '<li data-theme="'+data_theme+'"><h4>' + comments[i].user_handle + '</h4><p>' + comments[i].comments + '</p></li>';
            $('#legislation_comment_list').append(li);
        }
        $('#legislation_comment_list').listview('refresh');
        if (disable_comments) {
            $('#legislation_comment_form').slideUp();
        }
    };

    meo.displayLegislators = function(sponsors) {
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
    };

    meo.validated = function() {
        return true;
        if (util.check_phone()) {
            return true;
        }
    };


/*
    Callback functions for processing JSON data received from server for insertion into HTML
*/
    meo.callback = {

        login_check : function(data) {
console.log('meo.callback.login_check');
            if (data.user_id) {
                App.user_id = data.user_id;
                $.mobile.changePage('index.html');
            } 
            else {
                $.mobile.changePage('login.html?invalid');
            }            
        },

        first_time : function(data) {
console.log('meo.callback.first_time');
console.log(data);
            var _mymeo = util.getSessionData();
            var _issue_type = _mymeo && _mymeo.issue && _mymeo.issue.type;
            if (_issue_type == Constants.ANNOUNCEMENT_TYPE) {
                var _type_name = 'Announcement'; 
            }
            else {
                var _type_name = 'Legislation'; 
                $('.legislation_bill_id').html('Bill: ' + data.bill_id);
            }
            var _sponsor_name = data.sponsors && data.sponsors[0] && data.sponsors[0].name || 'No name provided';
            $('.sponsor_name').html(_sponsor_name);
            $('.issue_type').html(_type_name);
            $('.issue_title').html(data.title);
        },

        settings_city_list : function(data) {
console.log('meo.callback.settings_city_list');
            var cities = data.cities;
            var el;
            $('#settings .city_list li').remove();
            var li = meo.buildCityList(cities);
console.log(li);
            $('#settings .city_list').html(li);
            $('#settings .city_list a').click(meo.call.save_city);
            $('#settings .city_list').listview('refresh');
            $.mobile.hidePageLoadingMsg();
        },

        index_city_list : function(data) {
console.log('meo.callback.index_city_list');
            // When a user visits for the first time and selects a city,
            // the server returns the user ID, which should be placed in
            // local storage.
            var _user = MeoStorage.get_user();
            _user.user_id = data.user_id;
            MeoStorage.set_user(_user);

            var cities = data.cities;
            var li = meo.buildCityList(cities);
            $('#index_city .city_list').html(li);
            $('#index_city .city_list a').click(meo.call.save_city);
            $('#index_city .city_list').listview('refresh');
            $.mobile.hidePageLoadingMsg();
        },

/*
        save_city_inter : function(data) {
console.log('meo.callback.save_city_inter');
            MeoData('force');
        },
*/

        save_city : function(data) {
console.log('meo.callback.save_city');
console.log(data);
            util.setSessionData(data);
            $.mobile.changePage('index.html');
        },

        search : function(data) {
console.log('meo.callback.search');
console.log(data);
            if (data.length == 0) {
                $('.no_search_results').show();
            }
            else {
                $('.no_search_results').hide();
            }
            meo.makeList($('#index'), $('#search_tag_list'), data, { key_param: 'tid', unchecked_link : 'issues.html' });
            $('#search_tag_list').listview('refresh');
        },

        // meo.callback.settings
        settings : function(data) {
console.log('meo.callback.settings');
console.log(data);
            $('#person_last').val(data.last);
            $('#person_first').val(data.first);
            $('#person_phone').val(data.phone);
            meo.makeList($('#settings'), $('#tag_list'), data.tags, { checkboxes : true });

            $('.settings_zip').val('');
        },

        settings_save : function(data) {
console.log('meo.callback.settings_save');
            var _user = MeoStorage.get_user();
            _user.phone = data.phone;
            MeoStorage.set_user(_user);
            $.mobile.changePage('index.html#index');
        },

        alert_manager : function(data) {
console.log('meo.callback.alert_manager');
            $.mobile.hidePageLoadingMsg();
        },

        alert_manager_save : function(data) {
console.log('meo.callback.alert_manager_save');
            $.mobile.hidePageLoadingMsg();
            if (data.success) {
                $.mobile.changePage('index.html');
            }
        },

        // List of Announcements / Legislation
        legislation_categories : function(data) {
console.log('meo.callback.legislation_categories');
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
                MeoData.setProp('issue.category_name', data.category);
                MeoData.setProp('issue.category_id', data.filter);
                MeoData.setProp('issue.filter_type', data.filter_type);
                from = ['_LINK_', '_ID_', '_SRC_', '_TITLE_'];
                template = 
            		'<li><a href="_LINK_.html?id=_ID_">' +
            		'<h3>_TITLE_</h3>' +
                    '</a></li>';
                var issues = data.issues;
                var item;
                if (issues.length > 0) {
                    for (var i = 0, j = issues.length; i < j; i++) {
                        var issue = issues[i];
                        var key = issue.key;
                        var type = issue.type;
                        var link = type == Constants.LEGISLATION_TYPE ? 'legislation' : 'announcement';
                        var value = issue.value;
                        var image = issue.image || '';
                        var to = [link, key, image, value];
                        item = template.str_replace(from, to); 
                        $('#issues .issue_list').append(item);
                    }
                }
                else {
                    item = '<li>Nothing in this category</li>';
                    $('#issues .issue_list').append(item);
                }
                $('#issues .issue_list').listview('refresh');
            }
            $.mobile.hidePageLoadingMsg();
        },

        // Announcement data

        announcement : function(data) {
console.log('meo.callback.announcement');
console.log(data);
//            meo.setAnnouncementHeaderProps(data);
//            meo.pageHeader(); 
            util.updateDictionary(data);
            util.setClassTags();
            var calendared = util.fmtDate(data.calendared);
            var days_remaining = data.days_remaining;
            MeoData.setProp('issue.title', data.title);
            MeoData.setProp('issue.question', data.question);
            $('.announcement_title').html(data.title);
            $('.announcement_content').html(data.description);
            $('.announcement_question').html(data.question);
            if (data.user_feedback) {
                meo.announcementFeedbackProvided(data.user_feedback);
            }
            $.mobile.hidePageLoadingMsg();
        },

        announcement_save : function(data) {
console.log('meo.callback.announcement_save');
            if (data.feedback_id) {
                MeoData.setProp('issue.feedback_id', data.feedback_id);
            }
            meo.announcementComments(data.comment_data);
            $.mobile.changePage('announcement_feedback_response.html');
        },


        announcement_feedback : function(data) {
console.log('meo.callback.announcement_feedback');
console.log(data);
//            meo.pageHeader();
            util.setClassTags();
            var _mymeo = util.getSessionData();
            var title = data.title;
            var vote = util.fmtDate(data.vote);

            var question = data.question;
            var support = data.support;
            var oppose = data.oppose;
            var total = support + oppose;
            var image = data.image;
            // .header_link here overrides setting in pageHeader
            $('.header_link').attr('href', 'announcement.html?id=' + _mymeo.issue.id);
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
            $('#announcement_feedback_response input[type="button"]').click(function() {
                var comments = $('#announcement_feedback_response textarea').val();
                var url = Constants.server + Constants.page.announcement_save;
                var data = 'id=' + _mymeo.issue.id + '&fid=' + _mymeo.issue.feedback_id + '&comments=' + escape(comments);
console.log(url+'?'+data);
                util.serverCall(url, data, meo.callback.announcement_save);
            });
            meo.announcementComments(data.comment_data);
            $.mobile.hidePageLoadingMsg();
        },

        // Legislation data

        legislation : function(data) {
console.log('meo.callback.legislation');
console.log(data);
//            meo.setLegislationHeaderProps(data);
//            meo.pageHeader(); 
            util.updateDictionary(data);
            util.setClassTags();
            var summary = data.summary;
            MeoData.setProp('issue.title', data.title);
            MeoData.setProp('issue.category_id', data.category_id);
            MeoData.setProp('issue.category_name', data.category_name);
            $('.legislation_bill_id').html(data.bill_id);
            $('.legislation_title').html(data.title);
            $('.legislation_location>span').after(data.current_location);
            $('.legislation_chamber>span').after(data.current_chamber);
            $('.legislation_status').html(data.status);
            $('.legislation_content').html(summary);
            if (data.user_feedback) {
                meo.legislationFeedbackProvided(data.user_feedback);
            }
            $.mobile.hidePageLoadingMsg();
        },

        legislation_save : function(data) {
console.log('meo.callback.legislation_save');
console.log(data);
            if (data.feedback_id) {
                MeoData.setProp('issue.feedback_id', data.feedback_id);
            }
            meo.legislationComments(data.comment_data);
            $.mobile.changePage('legislation_feedback_response.html');
        },

        legislation_feedback : function(data) {
console.log('meo.callback.legislation_feedback');
//            meo.pageHeader(); 
            util.setClassTags();
            var _mymeo = util.getSessionData();
            var vote = util.fmtDate(data.vote);
            var sponsor_lastname = data.sponsors[0].lastname;
            var sponsor_title = data.sponsors[0].office;

            var question = data.question;
            var support = data.support;
            var oppose = data.oppose;
            var total = support + oppose;
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
            $('.sponsor_title').html(sponsor_title);
            $('.sponsor_lastname').html(sponsor_lastname);
            $('#legislation_feedback_response input[type="button"]').click(function() {
                var comments = $('#legislation_feedback_response textarea').val();
                var url = Constants.server + Constants.page.legislation_save;
                var data = 'id=' + _mymeo.issue.id + '&fid=' + _mymeo.issue.feedback_id + '&comments=' + escape(comments);
                util.serverCall(url, data, meo.callback.legislation_save);
            });
            meo.legislationComments(data.comment_data);
//            meo.displayLegislators(data.sponsors);
            $.mobile.hidePageLoadingMsg();
        },

        // Data for View Comments page

        view_comments : function(data) {
console.log('meo.callback.view_comments');
            $.mobile.hidePageLoadingMsg();
        },

        // Data for Tell Friends page

        tell_friends : function(data) {
console.log('meo.callback.tell_friends');
            var page;
/*
            var _mymeo = util.getSessionData();
            if (_mymeo.issue.type == Constants.ANNOUNCEMENT_TYPE) {
                page = 'announcement_feedback_response.html';
            }
            else {
                page = 'legislation_feedback_response.html';
            }
*/
            page = 'index.html';
            $.mobile.changePage(page);
        },

        friends_message_send : function(data) {
console.log('meo.callback.friends_message_send');
            $.mobile.hidePageLoadingMsg();
        },

        // Data for Alerts page
        alerts : function(data) {
console.log('meo.callback.alerts');
console.log(data);
            var el = [], tag;
            var issueLink;
            var _mymeo = util.getSessionData();
            if (_mymeo.issue.type == Constants.ANNOUNCEMENT_TYPE) {
                issueLink = 'announcement.html?id=' + _mymeo.issue.id;
            }
            else {
                issueLink = 'legislation.html?id=' + _mymeo.issue.id;
            }
            var title = _mymeo.issue.title;
//            meo.pageHeader(); 
            util.setClassTags();
            $('.header_link').attr('href', issueLink);
            $('.header_title').html(title);
            if (data.alerts) {
                meo.makeList($('#alerts'), $('#tag_list'), data.alerts, { checkboxes : true });
                $.mobile.hidePageLoadingMsg();
            }
            else if (data.success) {
                var _mymeo = util.getSessionData();
                var next = 'index.html';
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
            util.serverCall(url, data, meo.callback.login_check);
        },

        first_time : function() {
console.log('meo.call.first_time');
            var url;
            var data;
            var _fyi = MeoStorage.get_fyi();
            MeoData.setProp('issue.category_id', _fyi.issue.category_id);
            MeoData.setProp('issue.category_name', _fyi.issue.category_name);
            meo.setGeneralHeaderProps();
            meo.pageHeader();

            $('#first_time_button').click(function() {
                $.mobile.changePage(_fyi.page);
            });
            if (_fyi.issue.type == Constants.ANNOUNCEMENT_TYPE) {
                url = Constants.server + Constants.page.announcement;
                data = "id=" + _fyi.issue.id;
            }
            else if (_fyi.issue.type == Constants.LEGISLATION_TYPE) {
                url = Constants.server + Constants.page.legislation;
                data = "id=" + _fyi.issue.id;
            }
            util.serverCall(url, data, meo.callback.first_time);
    
        },

        index_city : function() {
console.log('meo.call.index_city');
            if (arguments.length == 0) {
                Geo.getZip.getLocation();
            }

            var geo_zip = arguments[0] || '';
            $('#index_city .settings_zip').val(geo_zip)
                .keyup(function() {
                    var zip = $('#index_city .settings_zip').val();
                    if (zip.length == 5) {
                        meo.call.index_city(zip);
                    }
                });
            if (geo_zip.length == 5) {
                $.mobile.showPageLoadingMsg();
                var url = Constants.server + Constants.popup.city_select;
                var data = 'zip=' + geo_zip;
                util.serverCall(url, data, meo.callback.index_city_list);
            }

        },

        settings_city : function() {
console.log('meo.call.settings_city');
            if (arguments.length == 0) {
                Geo.getZip.getLocation();
            }

            var geo_zip = arguments[0] || '';
            $('.settings_zip')
                .keyup(function() {
                    var zip = $('.settings_zip').val();
                    if (zip.length == 5) {
                        meo.call.settings_city(zip);
                    }
                });
            if (geo_zip.length == 5) {
                $.mobile.showPageLoadingMsg();
                var url = Constants.server + Constants.popup.city_select;
                var data = 'zip=' + geo_zip;
                util.serverCall(url, data, meo.callback.settings_city_list);
            }

        },

        save_city : function() {
console.log('meo.call.save_city');
            var city_id = $(this).attr('data-id');
            var url = Constants.server + Constants.process.save_city;
            var data = 'city_id=' + city_id;
            util.serverCall(url, data, meo.callback.save_city);
        },

        // Top navigation pages

        index : function() {
console.log('meo.call.index');
            var _mymeo = util.getSessionData();
            var data;
            var url;
            $.mobile.showPageLoadingMsg();
//            meo.setGeneralHeaderProps();
            util.setClassTags();
            if (_mymeo = util.getSessionData()) {
                meo.pageHeader();
                url = Constants.server + Constants.page.filter_tags;
                $('#search')
                    .focus(function() {
                        $('.no_search_results').hide();
                    })
                    .keyup(function() {
                        var searchFor = $(this).val();
                        if (searchFor.length >= 3) {
                            data = 'search='+searchFor;
                            util.serverCall(url, data, meo.callback.search);
                        }
                    });
                $('#index_city .settings_zip').val('')
                    .keyup(function() {
                        var zip = $('#index_city .settings_zip').val();
                        if (zip.length == 5) {
                            meo.call.index_city(zip);
                        }
                    });
            }
            $.mobile.hidePageLoadingMsg();
        },

        search : function(data) {
console.log('meo.call.search');
            // Most of this doesn't get processed unless a search value has been entered.
            if (data) {
                var url = Constants.server + Constants.page.issues;
                var data = 'search=' + escape(data);
//                util.serverCall(url, data, meo.callback.issues);
//                $.mobile.changePage('issues.html');
            }
        },

        settings : function() {
console.log('meo.call.settings');
            var _mymeo;
            if (_mymeo = util.getSessionData()) {
                var url = Constants.server + Constants.page.settings;
                var data = 'mode=1';
                $('#settings .save_alerts').click(function() {
                    meo.call.alert_manager_save();
                });
                $('#settings .save_contact').click(function() {
                    meo.call.settings_save();
                });
                $('#settings_zip')
                    .keyup(function() {
                        var zip = $('#settings_zip').val();
                        if (zip.length == 5) {
                            meo.call.settings_city(zip);
                        }
                    });
                $('#settings_logout').click(function() {
                    MeoData.logout();
                });
                util.serverCall(url, data, meo.callback.settings);
            }
        },

        settings_save : function() {
console.log('meo.call.settings_save');
            var last = $('#person_last').val();
            var first = $('#person_first').val();
            var phone = $('#person_phone').val();
            
            var url = Constants.server + Constants.page.settings_save;
            var data = 'last='+last+'&first='+first+'&phone='+phone;
            util.serverCall(url, data, meo.callback.settings_save);
        },

        about : function() {
console.log('meo.call.about');
            var _mymeo;
            if (_mymeo = util.getSessionData()) {
                meo.pageHeader(); 
                $('.region_heading_about').html(_mymeo.current_region && _mymeo.current_region.label_about);
            }
        },

        alert_manager : function() {
console.log('meo.call.alert_manager');
            $.mobile.showPageLoadingMsg();
            var url = Constants.server + Constants.page.alert_manager;
            var data = 'mode=1';
            util.serverCall(url, data, meo.callback.alert_manager);
        },

        alert_manager_save : function() {
console.log('meo.call.alert_manager_save');
            $.mobile.showPageLoadingMsg();
            var del_items = [];
            $('#settings input[type="checkbox"]:not(:checked)').each(function() {
                del_items.push('del_item[]=' + this.value);
            });
            var url = Constants.server + Constants.page.alert_manager;
            var data = del_items.join('&');
            util.serverCall(url, data, meo.callback.alert_manager_save);
        },


        // Announcement / Legislation list

        // meo.call.issues
        issues : function() {
console.log('meo.call.issues');
            var _mymeo;
            if (_mymeo = util.getSessionData()) {
                var _cat_id = util.queryString('id');
                var _location = util.queryString('loc');
                var _tag_id = util.queryString('tid');
//                meo.setGeneralHeaderProps();
//                meo.pageHeader(); 
                util.setClassTags();

                var data;
                var url = Constants.server + Constants.page.issues;
                if (_location) {
                    data = 'l=' + _location;
                }
                else if (_tag_id){
                    data = 't=' + _tag_id;
                }
                else {
                    data = 'c=' + _cat_id;
                }
                util.serverCall(url, data, meo.callback.issues);
            }
        },

        // Announcement details pages
        // meo.call.announcement
        announcement : function() {
console.log('meo.call.announcement');
            $.mobile.showPageLoadingMsg();
            var id = parseInt(util.queryString('id'));
            var url = Constants.server + Constants.page.announcement;
            MeoData.setProp('issue.id', id);
            MeoData.setProp('issue.type', Constants.ANNOUNCEMENT_TYPE);
            MeoData.setProp('issue.feedback_id', null);
            $('.button_green').click(function() { meo.call.announcement_save(1); });
            $('.button_red').click(function() { meo.call.announcement_save(-1); });
            var data = 'id=' + id;
            util.serverCall(url, data, meo.callback.announcement); 
        },

        announcement_save : function(response) {
console.log('meo.call.announcement_save');
            var _mymeo;
            var data;
            if (_mymeo = util.getSessionData()) {
                $.mobile.showPageLoadingMsg();
                var url = Constants.server + Constants.page.announcement_save;
                data = 'id='+_mymeo.issue.id+'&response='+response; 
console.log(url+'?'+data);
                util.serverCall(url, data, meo.callback.announcement_save);
            }
        },

        announcement_feedback : function() {
console.log('meo.call.announcement_feedback');
            $.mobile.showPageLoadingMsg();
            $('#announcement_comments').val('');
            var _mymeo;
            if (_mymeo = util.getSessionData()) {
                if (!_mymeo.issue) {
                    $.mobile.changePage('issues_announcements.html');
                    return false;
                }
                var id = parseInt(util.queryString('id')) || _mymeo.issue.id;
                var url = Constants.server + Constants.page.announcement;
                var data = 'id=' + id;
                util.serverCall(url, data, meo.callback.announcement_feedback);
            }
        },

        // Legislation details pages

        legislation : function() {
console.log('meo.call.legislation');
            var _mymeo;
            if (_mymeo = util.getSessionData()) {
                $.mobile.showPageLoadingMsg();
                var id = parseInt(util.queryString('id'));
                var url = Constants.server + Constants.page.legislation;
                MeoData.setProp('issue.id', id);
                MeoData.setProp('issue.type', Constants.LEGISLATION_TYPE);
                MeoData.setProp('issue.feedback_id', null);
                $('.button_green').click(function() { meo.call.legislation_save(1); });
                $('.button_red').click(function() { meo.call.legislation_save(-1); });
                var data = 'id=' + id;
console.log(url+'?'+data);
                util.serverCall(url, data, meo.callback.legislation);
            }
        },

        legislation_feedback : function() {
console.log('meo.call.legislation_feedback');
            $.mobile.showPageLoadingMsg();
            $('#legislation_comments').val('');
            var _mymeo;
            if (_mymeo = util.getSessionData()) {
                if (!_mymeo.issue) {
                    $.mobile.changePage('issues_legislation.html');
                    return false;
                }
                var id = parseInt(util.queryString('id')) || _mymeo.issue.id;
                var url = Constants.server + Constants.page.legislation;
                var data = 'id=' + id;
                util.serverCall(url, data, meo.callback.legislation_feedback);
            }
        },

        legislation_save : function(response) {
console.log('meo.call.legislation_save');
            if (!meo.validated()) {
                $.mobile.changePage($('#login'), { transition:'pop', reverse:true, changeHash:false });
            }
            else {
                var _mymeo;
                var data;
                if (_mymeo = util.getSessionData()) {
                    $.mobile.showPageLoadingMsg();
                    var url = Constants.server + Constants.page.legislation_save;
                    data = 'id='+_mymeo.issue.id+'&response='+response;
                    util.serverCall(url, data, meo.callback.legislation_save);
                }
            }
        },

        feedback_comments : function() {
console.log('meo.call.feedback_comments');
            var _mymeo;
            var fid;
            var response;
            if (_mymeo = util.getSessionData()) {
                if (_mymeo.issue.type == Constants.ANNOUNCEMENT_TYPE) {
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
console.log('meo.call.view_comments');
            $.mobile.showPageLoadingMsg();
            var _mymeo;
            if (_mymeo = util.getSessionData()) {
                var url = Constants.server + Constants.page.view_comments;
                var data = 'type=' + _mymeo.issue.type + '&id=' + _mymeo.issue.id;
                util.serverCall(url, data, meo.callback.view_comments);
            }
        },

        // Tell Friends page

        tell_friends : function() {
console.log('meo.call.tell_friends');
            var _mymeo;
            if (_mymeo = util.getSessionData()) {
                var title = _mymeo.issue.title;
                $('#tell_friends_title').html(title);
                $('#tell_friends input[type="button"]').click(function() {
console.log('Tell Friend send button clicked.');
                    if ( util.valPhone($('#friend_phone').val()) ) {
                        var params = [];
                        params.push('t=' + _mymeo.issue.type);
                        params.push('id=' + _mymeo.issue.id);
                        params.push('city_id=' + _mymeo.region[Constants.REGION_CITY].id);
                        params.push('phone=' + escape($('#friend_phone').val()));
                        params.push('message=' + escape($('#message').val()));
                        $('#friend_phone').val('');

                        var url = Constants.server + Constants.page.tell_friends;
                        var data = params.join('&');
                        util.serverCall(url, data, meo.callback.tell_friends);
                        console.log('Send SMS to friend.');
                    }
                });
            }
        },

/*
        fyi : function() {
console.log('meo.call.fyi');
        },
*/

        friends_message : function(friends) {
console.log('meo.call.friends_message');
            $.mobile.showPageLoadingMsg();
            var _mymeo;
            if (_mymeo = util.getSessionData()) {
                var contacts = [];
                $('#id').val(_mymeo.issue.id);
                $('#type').val(_mymeo.issue.type);
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
console.log('meo.call.friends_message_send');
            $.mobile.showPageLoadingMsg();
            var url = Constants.server + Constants.page.friends_message_send;
            util.serverCall(url, data, meo.callback.friends_message_send);
        },

        // Keep apprised page

        alerts : function() {
console.log('meo.call.alerts');
            $.mobile.showPageLoadingMsg();
            var _mymeo;
            if (_mymeo = util.getSessionData()) {
                if (!_mymeo.issue) {
                    $.mobile.changePage('index.html');
                    return false;
                }
                $('#alerts_save').click(function() {
                    var id = parseInt(_mymeo.issue.id);
                    var type = _mymeo.issue.type;
                    var url = Constants.server + Constants.page.alerts;
                    var data = 'mode=2&type='+type+'&id='+id;
                    var alerts = [];
                    $('#alerts input[type="checkbox"]:checked').each(function() {
                        alerts.push('item[]=' + this.value);
                    });
                    data += '&'+alerts.join('&');
                    util.serverCall(url, data, meo.callback.alerts);
                });
                var id = parseInt(_mymeo.issue.id);
                var type = _mymeo.issue.type;
                var url = Constants.server + Constants.page.alerts;
                var data = 'mode=1&type='+type+'&id='+id;
console.log(url+'?'+data);
                util.serverCall(url, data, meo.callback.alerts);
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

    $(document).delegate('#first_time', 'pageshow', function() {
console.log('#first_time pageshow fired');
        var _fyi = MeoStorage.get_fyi();
        if (_fyi.page) {
console.log('New page: ' + _fyi.page);
            meo.call.first_time();
//            $.mobile.changePage(_fyi.page);
        }
    });

    $(document).delegate('#index', 'pageshow', function() {
console.log('#index pageshow fired');
        meo.call.index();
    });

    $(document).delegate('#index_city', 'pageshow', function() {
        console.log('pageshow #index_city fired');
        meo.call.index_city();
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


    $(document).delegate('#legislation_feedback', 'pageshow', function() {
        meo.call.feedback_comments();
    });
    // View Comments page

    $(document).delegate('#view_comments', 'pageshow', function() {
        meo.call.view_comments();
    });

    // Tell Friends page

    $(document).delegate('#tell_friends', 'pageshow', function() {
        meo.call.tell_friends();
    });

    $(document).delegate('#fyi', 'pageshow', function() {
        meo.call.fyi();
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
        meo.call.alerts();
    });

