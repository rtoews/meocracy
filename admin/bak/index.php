<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');


ob_start();
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
<p>Select the region.  You can indicate it by entering a five-digit ZIP code.</p>
<form method="post" action="/ajax/save_city.php">
    <label for="zip"><?php echo $UIText['signup']['zip']; ?></label><input type="text" id="zip" name="zip" value="<?php echo isset($zip) ? $zip : ''; ?>"/><br/>
    <div id="city_list_container">
        <ul id="city_list"></ul>
    </div>
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"/>
    <input type="hidden" name="city_id" id="city_id" value=""/>
    <input type="hidden" name="county_id" id="county_id" value=""/>
    <input type="hidden" name="state_id" id="state_id" value=""/>
</form>
<script type="text/javascript">
var Signup = {
    zip: null
};

Signup.saveCity = function(city_id, county_id, state_id) {
    $('#city_id').val(city_id);
    $('#county_id').val(county_id);
    $('#state_id').val(state_id);
    $('form').submit();
};

Signup.cityList = function(data) {
    var el;
    if (data.length == 1) {
        Signup.saveCity(data[0].key, data[0].county_id, data[0].state_id);
    }
    else {
        for (var i = 0, j = data.length; i < j; i++) {
            el = document.createElement('li');
            $(el)
                .attr({id: data[i].key, 'data-county_id': data[i].county_id, 'data-state_id': data[i].state_id })
                .html(data[i].value)
                .click(function() {
                    Signup.saveCity($(this).attr('id'), $(this).attr('data-county_id'), $(this).attr('data-state_id'));
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
$page = ob_get_clean();
$css = '';
$replace = array(
    'from' => array('%CSS%', '%TITLE%', '%H1%', '%CONTENT%'),
    'to' => array($css, 'Admin', 'Admin', $page)
);

$template = file_get_contents(DOC_ROOT . '/admin/includes/template.php');
$output = str_replace($replace['from'], $replace['to'], $template);
print $output;
?>
