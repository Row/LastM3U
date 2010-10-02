$(document).ready(function ()
{
    $('#loader').hide();
    $('#type').bind('change', function(e)
    {
        $('#label').text($('#type option:selected').parent().attr('label')+'name');
        $('#user').attr('value','').focus();
    });

    $('#lastform').submit(function()
    {
        if($('#type').attr('value') == 'false') {
            varningShow('Choose a valid target.');
            return false
        }
        if($('#user').attr('value') == '') {
            varningShow('Enter a valid group, user or tag');
            return false
        }
        if($('#file').attr('value') == '') {
            varningShow('You must choose a m3u-file');
            return false
        }
        $('#loader').width($(document).width());
        $('#loader').height($(document).height());
        $('#loader').show();
        $('#form').hide();
        $('#resultBox').show();
        return true
    });

    $('#reset').click(function()
    {
        $('#form').show();
        $('#resultBox').hide();
        return true
    });

    $('#varningButton').click(function()
    {
        $('#form').show();
        $('#resultBox').hide();
        $('#loader').hide();
        $('#varning').hide();
        return true
    });

});

function varningShow(m)
{
    $('#varningInfo').text(m);
    $('#varning').css('left',$(document).width()/2 - $('#varning').width()/2).show();
}
function m3uPrepare(data)
{
    $('#found').html('');
    $('#notfound').html('');
    if(data['error']) {
        varningShow(data['error']);
        return false;
    }
    $('#found').append('Found ('+data['found'].length+')');
    $('#found').append('<input type="hidden" name="type" value="' + $('#type').attr('value') + '"/>');
    $('#user').clone().prependTo("#found").hide();
    $.each(
        data['found'],
        function(i, val)
        {
            $('#found').append($( '<li>' + val['artist'] + ' - ' + val['track'] + '</li>' ))
            $('#found').append('<ol></ol>')
            $.each(
                val['playlist'],
                function(i1,  playListEntry){
                     $('#found ol:last').append($( '<li>' + playListEntry + '</li>' + '<input name="tracks[]" type="hidden" value="' + playListEntry + '" />'))
                }
             )
        }
    );

    $.each(
        data['notfound'],
        function(i,  val){
             $('#notfound').append($( '<li>'  + val['artist'] + ' - ' + val['track'] + '</li>' ))
        }
     )
    $('#found li').bind("click", function(e)
    {
           if($(this).next().is("input")) {
            //toggle disable
            if($(this).next().attr('disabled') == true) {
                $(this).next().removeAttr('disabled');
            } else {
                $(this).next().attr('disabled',true);
            }
            $(this).toggleClass('notsel');
        } else if($(this).next().is("ol")) {
            //alert('all');
        }
    })
    $('#loader').hide();
}
