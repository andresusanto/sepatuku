{$_array_tabs = [
    [
        'title' => 'Account Info',
        'page' => 'account',
        'link' => {$links.account}
    ],
    [
        'title' => 'Change Password',
        'page' => 'account_edit_password',
        'link' => {$links.account_change_password}
    ],
    [
        'title' => 'Order History',
        'page' => 'order_list',
        'link' => {$links.account_orders}
    ]
]}
<!-- <ul> -->
{foreach $_array_tabs as $side_nav}
    {$_first = ''}
    {if $side_nav@first}
        {$_first = 'first'}
    {/if}
    {$_active = ''}
    {if isset($_sidename) && $_sidename==$side_nav.page}
        {$_active = 'active'}
    {/if}
    <div class="{$_active} {$_first} s-top-margin"><a class="attention" href="{$side_nav.link}" >{$side_nav.title}</a></div>
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
<div class="{$_active} {$_first} s-top-margin"><a class="attention" href="{$side_nav.link}" >{$side_nav.title}</a></div>

{/if}
<!-- <ul> -->
