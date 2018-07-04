<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<a href="javascript:;" onclick = POSTDATA() style="width:100px;height:100px;">123456</a>
<script>
    function POSTDATA(){
        var strData = "strFunction=AddServiceAccount&npsc=&npsr=&sc=610096&sr=TE&sadn=1&sag=";
        $.ajax({
            cache: false,
            data: strData,
            dataType: "json",
            success: function (objData, strTextStatus) {
                $('.Loading').hide();

                if (objData.intResult != 1) {
                    MsgBox.Show(objData.strOutstring);
                }
                else {
                    /*AddServiceAccountToList(objData.objResult.service_account_sn, objData.objResult.service_account_id, strServiceAccountDisplayName, objData.objResult.display_name);
                    SetServiceAccountListHoverEvent();
                    $('#divAddAcountDialog').dialog('close');
                    $('#divAddAcountDialog2').dialog('open');
                    setTimeout("$('#divAddAcountDialog2').dialog('close');", 3000);

                    if ($('#ulServiceAccountList').children().length >= m_intServiceAccountAmountLimit) {
                        $('#divAddServiceAccount').hide();
                    }*/
                }
            },
            error: function (objXMLHttpRequest, strTextStatus) {
                MsgBox.Show("error");
            },
            complete: function (objXMLHttpRequest, strTextStatus) {
                MsgBox.Show("complete");
            },
            type: "POST",
            url: 'https://tw.beanfun.com/generic_handlers/gamezone.ashx'
        });
    }
</script>