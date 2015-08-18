<!-- 
SEPATUKU SIRCLO THEME 
 (c) 2015 by AMBISNIS.COM
 Enquiry: sales@ambisnis.com
-->

{extends file='includes/theme.tpl'}

{block name="body"}
<!-- PAGE CONTENT HERE -->
			<div class="row s-main-content">
				<div class="col-md-12 s-breadcrumb s-bottom-padding">
					<a href="{$links.home}">{sirclo_get_text text='home_title'}</a>
					<span>&nbsp;/&nbsp;</span>
					<a href="{$links.cart}">{sirclo_get_text text='cart_title'}</a>
				</div>
				<div class="col-md-12 s-content-title">
					<h1>{sirclo_get_text text='cart_title'}</h1>
					<hr/>
				</div>
				
				<div class="col-md-12 s-cart">
					<table class="s-table">
						<thead>
							<tr>
								<td class="text-center">ITEM</td>
								<td>PRICE</td>
								<td>QUANTITY</td>
								<td class="text-right">TOTAL</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<img src="img/one-shoe.jpg"/>
									<div class="product-description">Nike Ardilla<br/>SDK. 1231241</div>
								</td>
								<td>IDR 1.350.000</td>
								<td>
									<input type="text" value="1"/>
									<button class="btn blue-button">UPDATE</button>
									<a href="javascript:void(0);">[x] remove</a>
								</td>
								<td class="text-right">IDR 1.350.000</td>
							</tr>
							<tr>
								<td>
									<img src="img/one-shoe.jpg"/>
									<div class="product-description">Nike Ardilla<br/>SDK. 1231241</div>
								</td>
								<td>IDR 1.350.000</td>
								<td>
									<input type="text" value="1"/>
									<button class="btn blue-button">UPDATE</button>
									<a href="javascript:void(0);">[x] remove</a>
								</td>
								<td class="text-right">IDR 1.350.000</td>
							</tr>
							<tr>
								<td>
									<img src="img/one-shoe.jpg"/>
									<div class="product-description">Nike Ardilla<br/>SDK. 1231241</div>
								</td>
								<td>IDR 1.350.000</td>
								<td>
									<input type="text" value="1"/>
									<button class="btn blue-button">UPDATE</button>
									<a href="javascript:void(0);">[x] remove</a>
								</td>
								<td class="text-right">IDR 1.350.000</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="s-cart-conclusion">
					<div class="item-total col-xs-12 pull-right">
						<div class="col-xs-6 col-md-3 nopadding pull-right text-right">
							2.700.000
						</div>
						<div class="col-xs-6 col-md-3 pull-right text-right">
							Item total
						</div>
					</div>
					<div class="item-ship col-xs-12 pull-right">
						<div class="col-xs-6 col-md-3 nopadding pull-right text-right">
							50.000
						</div>
						<div class="col-xs-6 col-md-3 pull-right text-right">
							Shipping and handling
						</div>
					</div>
					<div class="total attention col-xs-12 pull-right">
						<div class="col-xs-6 col-md-3 nopadding pull-right text-right">
							IDR 2.750.000
						</div>
						<div class="col-xs-6 col-md-3 pull-right text-right">
							Total
						</div>
					</div>
					<div class="col-xs-12 text-right">
						<button class="col-xs-1 col-md-1 blue-button btn pull-right">UPDATE</button>
						<div class="col-xs-5 col-md-2 dropdown pull-right btn-group clearfix">
					    <button class="btn btn-default dropdown-toggle s-dropdown" type="button" id="menu1" data-toggle="dropdown"><span data-bind="label">Indonesia</span>
					    <span class="caret"></span></button>
					    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
					      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Indonesia</a></li>
					      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Malaysia</a></li>
					      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Singapore</a></li>
					      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Thailand</a></li>
					      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">USA</a></li>
					    </ul>
					  </div>
						<span class="col-xs-4 col-md-2 s-label pull-right">Shipping Country</span>
					</div>
					<div class="col-xs-12 text-right">
						<button class="col-xs-1 col-md-1 blue-button btn pull-right">UPDATE</button>
						<div class="col-xs-5 col-md-2 dropdown pull-right btn-group">
					    <button class="btn btn-default dropdown-toggle s-dropdown" type="button" id="menu1" data-toggle="dropdown"><span data-bind="label">USPS First Class</span>
					    <span class="caret"></span></button>
					    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
					      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">USPS First Class</a></li>
					      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">USPS First Class International</a></li>
					      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">USPS Priority</a></li>
					      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">USPS MediaMail</a></li>
					      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">USPS Two-Day</a></li>
					    </ul>
					  </div>
						<span class="col-xs-4 col-md-2 s-label pull-right">Shipping Method</span>
					</div>
					<div class="s-wavy-separator pull-right"></div>
					<a href="product.html" class="col-xs-4 col-md-7"><span class="s-large-font s-bebas attention"><i class="fa fa-angle-left"></i> CONTINUE SHOPPING</span></a>
					<span class="col-xs-8 col-md-5"><button class="btn btn-lg blue-button pull-right s-large-button s-to-checkout-page">CHECKOUT</button></span>
				</div>
					
			</div>
{/block}

{block name="footer"}
    <script type="text/javascript">
        $('.sirclo-form').validate();
    </script>
    {if !empty($configs.theme_facebook_appid)}
    <script type="text/javascript">
        $(document).ready(function() {
          $.getScript('//connect.facebook.com/en_UK/all.js', function(){
            FB.init({
              appId  : '{$configs.theme_facebook_appid}',
              cookie : true,  // enable cookies to allow the server to access
                              // the session
              xfbml  : true,  // parse social plugins on this page
              version: 'v2.0' // use version 2.0
              });
            });
          if (location.search) {
              $("#fbloginlink").facebooklogin("{$links.cart_place_order}");
          } else {
              $("#fbloginlink").facebooklogin("{$links.account_login}");                       
          }
        });
    </script>
    {/if}
{/block}
