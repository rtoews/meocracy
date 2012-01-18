<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');

$user_id = get_param('user_id');
$user = new User($user_id);

$html = new HTML();
$html->set_title('Settings');
$html->generate_header_mobile();
?>
<style type="text/css">
#city_list_container {
    position:relative;
    display:none;
    margin-left:105px;
}
#city_list {
    position:absolute;
    margin:0;
    padding:0;
    border:1px solid green;
    list-style-type:none;
}
#city_list li {
    cursor:pointer;
    padding:3px;
}
#city_list li:hover {
    background:#eee;
}
</style>
<p>Select the city you live in.  You can indicate it by entering your five-digit ZIP code.</p>
<form method="post" action="/ajax/save_city.php">
    <label for="zip"><?php echo $UIText['signup']['zip']; ?></label><input type="text" id="zip" name="zip" value="<?php echo isset($zip) ? $zip : ''; ?>"/><br/>
    <div id="city_list_container">
        <ul id="city_list"></ul>
    </div>
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"/>
    <input type="hidden" name="city_id" id="city_id" value=""/>
</form>
<script type="text/javascript">
var Signup = {
    zip: null
};

Signup.saveCity = function(city_id) {
    $('#city_id').val(city_id);
    $('form').submit();
/*
    $.ajax({
        url: '/ajax/save_city.php',
        tyle: 'POST',
        cache: false,
        data: data,
        success: function(response) {
            
        },
        error: function(jqXHR, textStatus, errorThrown) {
        }
    });
*/
};

Signup.cityList = function(data) {
    var el;
    if (data.length == 1) {
        Signup.saveCity(data[0].key);
    }
    else {
        for (var i = 0, j = data.length; i < j; i++) {
            el = document.createElement('li');
            $(el)
                .attr('id', data[i].key)
                .html(data[i].value)
                .click(function() {
                    Signup.saveCity($(this).attr('id'));
                });
    
            $('#city_list').append(el);        
        }
        $('#city_list_container').show();
    }
};

Signup.checkZip = function() {
    var zip = $('#zip').val();
    if (zip == Signup.zip) {
        return;
    }
    $('#city_list').html('');
    Signup.zip = zip;
    var data = 'zip=' + zip;
    if (zip.length == 5) {
        $.ajax({
            url: '/ajax/get_city_list.php',
            type: 'POST',
            dataType: 'json',
            cache: false,
            data: data,
            success: function(response) {
                Signup.cityList(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
            }
        
        });
    }
    else {
    }
}

Signup.initialize = function() {
    $('#zip').focus();
    $('#zip').keyup(function() {
        Signup.checkZip();
    });
};

$(document).ready(function() {
    Signup.initialize();
});
</script>
<?php
$html->generate_footer_mobile();
?>
