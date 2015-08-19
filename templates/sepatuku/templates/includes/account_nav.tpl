{$_array_tabs = [
    [
        'title' => 'Account Info',
        'page' => 'account',
        'link' => '{$links.account}'
    ],
    [
        'title' => 'Change Password',
        'page' => 'account_edit_password',
        'link' => '{$links.account_change_password}'
    ],
    [
        'title' => 'Order History',
        'page' => 'order_list',
        'link' => '{$links.account_orders}'
    ],
	[
        'title' => 'Invite Friends',
        'page' => 'account_invite',
        'link' => '{$links.account_invite}'
    ],
	[
        'title' => 'Logout',
        'page' => '/',
        'link' => 'javascript:void(0);'
    ]
]}

<div class="col-md-3 s-top-margin">
	<h2 class="nomargin">MY ACCOUNT</h2>
	{foreach $_array_tabs as $side_nav}
		<div class="active s-top-margin">
			<a class="attention" href="{$side_nav.link}">{$side_nav.title}</a>
		</div>
	{/foreach}
</div>

<!--
<ul>
{foreach $_array_tabs as $side_nav}
    {$_first = ''}
    {if $side_nav@first}
        {$_first = 'first'}
    {/if}
    {$_active = ''}
    {if isset($_sidename) && $_sidename==$side_nav.page}
        {$_active = 'active'}
    {/if}
    <li class="{$_active} {$_first}"><a href="{$side_nav.link}" >{$side_nav.title}</a></li>
{/foreach}

{if isset($links.account_invite)}

{$side_nav = [
        'title' => 'Invite Your Friend',
        'page' => 'account_invite',
        'link' => {$links.account_invite}
]}

{$_active = ''}
{if isset($_sidename) && $_sidename==$side_nav.page}
    {$_active = 'active'}
{/if}
<li class="{$_active} {$_first}"><a href="{$side_nav.link}" >{$side_nav.title}</a></li>

{/if}
<ul> -->
