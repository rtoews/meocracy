var Tag = {};

Tag.stopWords = Data.stopwords;

Tag.chosenTags = [];
Tag.customTags = [];

String.prototype.removePunctuation = function() {
    var nopunct = this;
    nopunct = nopunct.replace(/[^\w\s]/g, '');
    nopunct = nopunct.replace(/[\s+]/g, ' ');
    return nopunct.toLowerCase();
};

Tag.passesTest = function(str) {
    pass = true;
    if (str.length > 28) { // longer than antidisestablishmentarianism... fail!
        pass = false;
    }
    else if (str.indexOf('_') > -1) { // contains underscore... fail!
        pass = false;
    }
    else if (str.match(/[0-9]/)) { // contains digits... fail!
        pass = false;
    }
    return pass;
};

Array.prototype.removeNoiseWords = function() {
    var words = this;
    var filtered = [];
    for (var i = 0, j = words.length; i < j; i++) {
        if (Tag.passesTest(words[i])) {
            filtered.push(words[i]);
        }
    }
    return filtered;
};

Array.prototype.removeStopWords = function() {
    var words = this;
    var filtered = [];
    for (var i = 0, j = words.length; i < j; i++) {
        if ($.inArray(words[i], Tag.stopWords) == -1 && words[i]) {
            filtered.push(words[i]);
        }
    }
    return filtered;    
};

Array.prototype.removeDuplicates = function() {
    var words = this;
    var dup_free = [];
    for (var i = 0, j = words.length; i < j; i++) {
        if ($.inArray(words[i], dup_free) == -1 && $.inArray(words[i], Tag.chosenTags) == -1) {
            dup_free.push(words[i]);
        }
    }
    return dup_free;
};


Tag.makeTag = function(w) {
    var el = document.createElement('span');
    $(el).html(w);
    var outer_el = document.createElement('div');
    $(outer_el)
        .attr('data-word', w)
        .addClass('tag_wrap').append(el);
    return outer_el;
};

Tag.move = function(tag) {
    var container = tag.parent().attr('id');
    var new_container;
    if (container == 'tag_suggestions') {
        new_container = '#tag_choices';
        Tag.chosenTags.push(tag.attr('data-word'));

    }
    else {
        new_container = '#tag_suggestions';
        Tag.chosenTags = $.grep(Tag.chosenTags, function(n, i) {
            return tag.attr('data-word') != n;
        });
    }
    $(tag).appendTo(new_container);
};


Tag.error = function(jqXHR, textStatus, errorThrown) {
    console.log('Error: ' + textStatus);
    console.log(errorThrown);
    console.log(jqXHR);
};

Tag.serverCall = function(url, data, callback) {
    $.ajax({
        url: url,
        dataType: 'jsonp',
        data: data,
        success: callback,
        error: Tag.error
    });
};

Tag.callbackPresentTags = function(data) {
console.log('Tag.callbackPresentTags');
console.log(data);
    words = data.recognized;
    var el;
    $('#tag_suggestions').html('');
    for (var i = 0, j = words.length; i < j; i++) {
        if (words[i]) {
            el = Tag.makeTag(words[i]);
            $(el).click(function() { Tag.move($(this)); } );
            $('#tag_suggestions').append(el);
        }
    }
};

Tag.extractTags = function() {
    var str_array = [];
    $.each($('.tag_source'), function(index, value) {
        str_array.push($(value).val());
    });
    var str = str_array.join(' ');
    var nopunct = str.removePunctuation();
    var words = nopunct.split(' ');
    words = words.removeStopWords();
    words = words.removeDuplicates();
    words = words.removeNoiseWords();
    Tag.serverCall('spellcheck.php', { 'words' : words }, Tag.callbackPresentTags);
};

Tag.addCustomTag = function(str) {
    if (!str) return;
    var nopunct = str.removePunctuation();
    Tag.customTags.push(str);
    var el = Tag.makeTag(str);
    $(el)
        .click(function() {
            Tag.removeCustomTag($(this));
        })
        .appendTo('#tag_choices');
    $('#custom_tag').val('');
};

Tag.removeCustomTag = function(el) {
    var tag = $(el);
    Tag.customTags = $.grep(Tag.customTags, function (n, i) {
        return tag.attr('data-word') != n;
    });
    $('#tag_choices').find(tag).remove();
};

Tag.copyTagsToField = function() {
    $.merge(Tag.chosenTags, Tag.customTags);
    $('#tag_list').val(Tag.chosenTags.join('|'));
    $('form').submit();
};
