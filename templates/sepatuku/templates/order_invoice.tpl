<html>
<head>
<title>
{$title}
</title>
</head>
<body>
<div style="width:590px; padding:10px; margin:0px auto; font-family:'Arial',tahoma; font-size:12px;">
    <div style="width:100%; height:100px">
    <div style="float:left; height: 100px; width:50%;">
            <img src="{sirclo_resource file='images/logo.png'}" style="height:100px" />
    </div>
    <div style="float:right; height:75px; width:50%; text-align:right;">
    <span style="font-size:20px; font-weight:bold; display:block;">{sirclo_get_text text='invoice_title'}</span>
    <span style="font-size:14px; font-weight:bold; display:block; padding:15px 0px 0px">ID: {$order.invoice_id}</span>
    </div>
    </div>
    <div style="height:45px; clear:both;">
    <span style="font-size:20px; font-weight:bold; display:block;">{sirclo_get_text text='order_id_title'}: {$order.order_id}</span>
    </div>
        {sirclo_render_order_table order=$order}
        {sirclo_render_order_info order=$order}
    </div>
</body>
</html>
