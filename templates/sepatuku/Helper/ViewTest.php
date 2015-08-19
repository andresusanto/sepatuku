<?php
class Helper_ViewTest
{
    static function getLoremIpsumItems($size, $n)
    {
        $s = '';
        foreach (range(1, $n) as $i) {
            $s .= '* ' . self::getLoremIpsum($size) . "\n";
        }
        return $s;
    }

    static function getLoremIpsumItemsUl($size, $n)
    {
        $s = "<ul>\n";
        foreach (range(1, $n) as $i) {
            $s .= '<li>' . self::getLoremIpsum($size) . "</li>\n";
        }
        $s .= "</ul>\n";
        return $s;
    }

    static function getLoremIpsumP($size, $prefix='')
    {
        $lis = array(
            'Lorem ipsum <strong>dolor sit amet</strong>, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
            'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
        );
        $s = '';
        foreach (range(0, $size-1) as $id) {
            $s .= $lis[$id] . ' ';
        }
        return "<p>$prefix$s</p>\n";
    }

    static function getLoremIpsum($size)
    {
        $lis = array(
            'Lorem ipsum <strong>dolor sit amet</strong>, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
            'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
        );
        $s = '';
        foreach (range(0, $size-1) as $id) {
            $s .= $lis[$id] . ' ';
        }
        return $s;
    }

    static function getTestMarkdown()
    {
        $markup = <<<EOD
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.

Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.

## This is the second heading
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim
![Wikipedia Logo 1](http://upload.wikimedia.org/wikipedia/en/b/bc/Wiki.png)
ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.

![This is a very very very very very very very very very very very very very very very long title](http://upload.wikimedia.org/wikipedia/en/b/bc/Wiki.png)

Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.

<img src="http://upload.wikimedia.org/wikipedia/en/b/bc/Wiki.png"/>

Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.

### This is the third heading
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.

# This is the first heading
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.

***

This is the first paragraph. **This text is bold**.
_This text is italic_.

> This is a block quote
This is still in the block quote
And so is this

> This is the first level of quoting.
>
> > This is nested blockquote.
>
> Back to the first level.

> ## This is a header.
> 
> 1.   This is the first list item.
> 2.   This is the second list item.
> 
> Here's some example code:
> 
>     return shell_exec("echo \$input | \$markdown_script");

1. This is the first ordered item.
2. This is the second ordered item.
3. This is the third ordered item.

* This is the first unordered item.
* This is the second unordered item.
* This is the third unordered item.

1.  This is a list item with two paragraphs. Lorem ipsum dolor
    sit amet, consectetuer adipiscing elit. Aliquam hendrerit
    mi posuere lectus.

    Vestibulum enim wisi, viverra nec, fringilla in, laoreet
    vitae, risus. Donec sit amet nisl. Aliquam semper ipsum
    sit amet velit.

2.  Suspendisse id sem consectetuer libero luctus adipiscing.

This is a normal paragraph:

    This is a code block.

    <div class="footer">
        &copy; 2004 Foo Corporation
    </div>

*****

This is [an example](http://example.com/ "Title") inline link.

[This link](http://example.net/) has no title attribute.

See my [About](/about/) page for details.

This is [an example][id] reference-style link.

[id]: http://example.com/  "Optional Title Here"

*****

Use the `printf()` function.

`&#8212;` is the decimal-encoded equivalent of `&mdash;`.

EOD;
        return $markup;
    }

    static function getTestMarkdownHtml()
    {
        $s = <<<EOD
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

<h2>This is the second heading</h2>

<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim
<img src="http://upload.wikimedia.org/wikipedia/en/b/bc/Wiki.png" alt="Wikipedia Logo 1" />
ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

<p><img src="http://upload.wikimedia.org/wikipedia/en/b/bc/Wiki.png" alt="This is a very very very very very very very very very very very very very very very long title" /></p>

<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

<p><img src="http://upload.wikimedia.org/wikipedia/en/b/bc/Wiki.png"/></p>

<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

<h3>This is the third heading</h3>

<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

<h1>This is the first heading</h1>

<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

<hr />

<p>This is the first paragraph. <strong>This text is bold</strong>.
<em>This text is italic</em>.</p>

<h4>This is the fourth heading</h4>

<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

<blockquote>
  <p>This is a block quote
  This is still in the block quote
  And so is this</p>
  
  <p>This is the first level of quoting.</p>
  
  <blockquote>
    <p>This is nested blockquote.</p>
  </blockquote>
  
  <p>Back to the first level.</p>
  
  <h2>This is a header.</h2>
  
  <ol>
  <li>This is the first list item.</li>
  <li>This is the second list item.</li>
  </ol>
  
  <p>Here's some example code:</p>

<pre><code>return shell_exec("echo \$input | \$markdown_script");
</code></pre>
</blockquote>

<ol>
<li>This is the first ordered item.</li>
<li>This is the second ordered item.</li>
<li>This is the third ordered item.</li>
</ol>

<ul>
<li>This is the first unordered item.</li>
<li>This is the second unordered item.</li>
<li>This is the third unordered item.</li>
<ul>
<li>This is the first nested item.</li>
<li>This is the second nested item.</li>
</ul>
<li>This is the fourth unordered item.</li>
</ul>

<ol>
<li><p>This is a list item with two paragraphs. Lorem ipsum dolor
sit amet, consectetuer adipiscing elit. Aliquam hendrerit
mi posuere lectus.</p>

<p>Vestibulum enim wisi, viverra nec, fringilla in, laoreet
vitae, risus. Donec sit amet nisl. Aliquam semper ipsum
sit amet velit.</p></li>
<li><p>Suspendisse id sem consectetuer libero luctus adipiscing.</p></li>
</ol>

<p>This is a normal paragraph:</p>

<pre><code>This is a code block.

&lt;div class="footer"&gt;
    &amp;copy; 2004 Foo Corporation
&lt;/div&gt;
</code></pre>

<hr />

<p>This is <a href="http://example.com/" title="Title">an example</a> inline link.</p>

<p><a href="http://example.net/">This link</a> has no title attribute.</p>

<p>See my <a href="/about/">About</a> page for details.</p>

<p>This is <a href="http://example.com/" title="Optional Title Here">an example</a> reference-style link.</p>

<p>This is a <a href="exec" target="popup">popup</a> link.</p>

<p>This is a <a href="exec" class="button">Button</a> link.</p>

<hr />

<p>Use the <code>printf()</code> function.</p>

<p><code>&amp;#8212;</code> is the decimal-encoded equivalent of <code>&amp;mdash;</code>.</p>

EOD;
        return $s;
    }

    static function _getTestProductSpecification()
    {
        $spec = Helper_ViewTest::getLoremIpsumP(2) . "\n" . "<h3>Specification</h3>\n\n" . Helper_ViewTest::getLoremIpsumP(2) . "\n" . Helper_ViewTest::getLoremIpsumP(1) . "\n<h3>Application</h3>\n\n" . Helper_ViewTest::getLoremIpsumP(3) . "\n<h3>Features</h3>\n\n" . Helper_ViewTest::getLoremIpsumP(2);
        return $spec;
    }

    static function _getTestDetailedImages($controller, $n, $basename='product')
    {
        $colors = array(
            'red',
            'light green',
            NULL,
        );
        $sizes = array(
            'S',
            'M',
            'S',
            NULL,
        );
        $files = array(
            $basename . '_a.jpg',
            $basename . '_b.jpg',
            $basename . '_c.jpg',
        );
        $images = array();
        for ($i = 0; $i < $n; ++$i) {
            $file = $files[$i%count($files)];
            $url = $controller->resource_url . '/images/test/' . $file;
            $color = $colors[$i%count($colors)];
            $size = $sizes[$i%count($sizes)];
            $image = array(
                'url' => $url,
                'color' => $color,
                'size' => $size,
                'file_basename' => "File Basename " . ($i+1),
            );
            $images[] = $image;
        }
        return $images;
    }

    static function getTestProduct($controller, $idx)
    {
        $short_description = Helper_ViewTest::getLoremIpsumP(1) . "\n" . Helper_ViewTest::getLoremIpsumP(1);
        if ($idx % 2) {
            $short_description = "<p>Very short description.</p>\n";
        }
        $description = Helper_ViewTest::getLoremIpsumP(2) . "\n" . Helper_ViewTest::getLoremIpsumP(1);
        if ($idx > 3) {
            $description = Helper_ViewTest::getLoremIpsumP(1);
        }
        $attachment = "http://attachment$idx";
        $product = array(
            'id' => $idx,
            'fid' => "product-$idx",
            'product_code' => "ABC$idx",
            'title' => "Product $idx" . (($idx%2) ? " Long Long Very Long Name" : ''),
            'short_description' => $short_description,
            'description' => $description,
            'brand' => "Brand $idx",
            'label' => "Category " . (($idx-1)%3+1),
            'label_fid' => "parent/category" . (($idx-1)%3+1),
            'specification' => self::_getTestProductSpecification(),
            'link' => "http://product$idx",
            //'price' => "$ " . $idx*500,
            'price_raw' => ($idx==16) ? 0 : $idx*500,
            'is_new' => !(bool)($idx%3),
            'is_featured' => !(bool)($idx%2),
            'size' => "Size $idx",
            'color' => "Color $idx",
            'images' => array(
                $controller->resource_url . "/images/test/product_${idx}a.jpg",
                $controller->resource_url . '/images/test/product_b.jpg',
                $controller->resource_url . '/images/test/product_a.jpg',
                $controller->resource_url . '/images/test/product_b.jpg',
                $controller->resource_url . '/images/test/product_a.jpg',
                $controller->resource_url . '/images/test/product_b.jpg',
            ),
            'detailed_images' => self::_getTestDetailedImages($controller, 12),
        );
        if ($idx==8) {
            $product['label'] = 'Backorder';
        }
        if (!($idx%2)) {
            $product['images'] = array(
                $controller->resource_url . "/images/test/product_${idx}a.jpg",
                $controller->resource_url . '/images/test/product_b.jpg',
            );
            $product['detailed_images'] = self::_getTestDetailedImages($controller, 4);
        }
        if ($idx == 6) {
            $product['images'] = array();
            $product['detailed_images'] = array();
        }
        if ($idx%2) {
            $product['attachment'] = $attachment;
        }
        if (isset($controller->krcoFunctions) && method_exists($controller->krcoFunctions, 'appendProduct')) {
            $controller->krcoFunctions->appendProduct($idx, $product);
        }
        $options = $controller->getObjKrcoConfig('options', 'products');
        if (is_array($options)) {
            $defKeys = Helper_Krco::getDefaultProductOptionKeys();
            foreach ($options as $key => $opt) {
                if (!in_array($key, $defKeys)) {
                    $product[$key] = "$key $idx";
                }
            }
        }
        $product = array_merge($product, Helper_ViewTest::getAdditionalTestFields($controller, 'products', $idx));
        if ($idx%2) {
            //$product['usual_price'] = '$ ' . ($idx*500+50);
            $product['usual_price_raw'] = ($idx*500+50);
            $product['sale_expiry_timestamp'] = $idx*86400;
        }
        return $product;
    }

    static function getTestEcommerceProduct($controller, $idx)
    {
        $product = self::getTestProduct($controller, $idx);
        $product['wish_count'] = $idx*7*7;
        $product['options'] = array();
        $product['general_options'] = array();
        if ($idx % 2) {
            $product['options']['size'] = array('S', 'M', 'L', 'XL');
        }
        if ($idx != 5 && $idx != 8) {
            $product['options']['color'] = array('Red', 'Light Green', 'Blue');
        }
        $decorations = self::_getProductDecorations($controller);
        if (isset($decorations[$idx-1])) {
            $decoration = Helper_URL::constructFid($decorations[$idx-1]);
            $product['decoration'] = $decoration;
        }
        $product['is_backorder'] = ($idx%3 == 1);
        $product['stock'] = (($idx-1)%4) + 1;
        $product['sold_stock'] = (($idx-1)%20) * 7;
        $product['is_in_stock'] = !(bool)($idx%3 == 1);
        $product['variants'] = array(
            '|Red' => 'available',
            '|Blue' => 'no_stock',
        );
        $product['detailed_variants'] = array(
            '|Red' => array(
                'price' => 5000.5,
                'stock' => 5,
            ),
            '|Blue' => array(
                'price' => 1000.5,
                'stock' => 0
            ),
        );
        if ($idx%2) {
            $product['variants'] = array(
                'S|Red' => 'no_stock',
                'M|Red' => 'available',
                'S|Light Green' => 'available',
                '|Blue' => 'no_stock',
            );
            $product['detailed_variants'] = array(
                'S|Red' => array(
                    'price' => 1000.5,
                    'stock' => 0,
                ),
                'M|Red' => array(
                    'price' => 2000,
                    'stock' => 1000000,
                ),
                'S|Light Green' => array(
                    'price' => 2000.50,
                    'stock' => 4,
                ),
                '|Blue' => array(
                    'price' => 10000,
                    'stock' => 0,
                ),
            );
        }
        $product['prices_by_color'] = array(
            'Red' => 20.5,
            'Blue' => 3000,
            'Pink' => 40,
        );
        if ($idx == 7) {
            $product['options']['size'] = array('S', 'M', 'L', 'XL');
            unset($product['options']['color']);
            $product['variants'] = array(
                'S|' => 'available',
                'L|' => 'no_stock',
            );
            $product['detailed_variants'] = array(
                'S|' => array(
                    'price' => 3000.5,
                    'stock' => 3,
                ),
                'L|' => array(
                    'price' => 1000.5,
                    'stock' => 0,
                ),
            );
        }
        if ($idx == 4) {
            $product['variants'] = array();
            $product['detailed_variants'] = array();
        }
        $titles = array('Size', 'Color');
        foreach ($product['options'] as $key => $val) {
            $i = 0;
            if ($key == 'size') {
                $i = 1;
            } else if ($key == 'color') {
                $i = 2;
            }
            $genkey = 'option' . $i;
            $genopt = array(
                'title' => isset($titles[($i-1)%2]) ? $titles[($i-1)%2] : "Option $i",
                'value' => "${genkey}_value",
                'options' => $val,
            );
            $product['general_options'][$genkey] = $genopt;
            $i++;
        }
        return $product;
    }

    static function _getProductDecorations($controller)
    {
        $decorations = array();
        if ($arr = $controller->getKrcoConfigValue('products', 'decorations')) {
            $decorations = $arr;
        }
        return $decorations;
    }

    static function getTestAddress($idx)
    {
        $address = array(
            'id' => "$idx",
            'title' => "Address $idx",
            //'salutation' => "Ms",
            'first_name' => "First Name $idx",
            'last_name' => "Last Name $idx",
            //'company' => "Company $idx",
            'address_line1' => "Address Line 1",
            'address_line2' => "Address Line 2",
            'postal_code' => "123455",
            'city' => "City $idx",
            'state' => "State $idx",
            'country' => "ID",
            'phone' => '12345678',
            'email' => "email$idx@example.com",
        );
        return $address;
    }

    static function getTestMember($controller, $idx)
    {
        $member = array(
            'member_code' => "MEMBER$idx",
            'salutation' => 'Ms',
            'first_name' => "Member $idx Long Name",
            'last_name' => "Last Name",
            'company' => "Company $idx",
            'email' => "email$idx@site.org",
            'address_line1' => 'Address Line 1',
            'address_line2' => 'Address Line 2',
            'postal_code' => '123456',
            'city' => "City $idx",
            'state' => "State $idx",
            'country' => 'SG',
            'nationality' => 'MY',
            'id_number' => 'ABC12345678',
            'phone' => '98765432',
            'phone2' => '23456789',
            'dob_timestamp' => strtotime('3 november 1973'),
            'npoints' => $idx*10,
            'member_level' => ($idx%2) ? "VIP" : "Level $idx",
            'wish_product_ids' => array(1, 3, 6, 10, 15),
            'images' => ($idx%2 && $controller) ? array(
                $controller->resource_url . "/images/test/member_${idx}a.jpg",
                $controller->resource_url . "/images/test/member_b.jpg",
                $controller->resource_url . "/images/test/member_c.jpg",
            ) : array(),
        );
        $member = Helper_ViewTest::getAdditionalTestFields($controller, 'account', $idx) + $member;
        if (!$idx) {
            foreach ($member as $key => &$val) {
                $val = NULL;
            }
        }
        return $member;
    }

    static function getTestEnquiry($controller, $idx)
    {
        $enquiry = array(
            'id' => $idx,
            'enquiry_timestamp' => strtotime("$idx July 2003"),
            'name' => "John $idx",
            'email' => "john$idx@email.com",
            'ip_address' => "192.168.0.$idx",
            'phone' => "1234000$idx",
            'company' => "Company $idx",
            'subject' => "Subject $idx",
            'message' => "Message $idx.\nThis is line 2.\n",
        );
        $enquiry = Helper_ViewTest::getAdditionalTestFields($controller, 'contact', $idx) + $enquiry;
        return $enquiry;
    }

    static function getTestOrder($controller, $idx)
    {
        $order = array(
            'id' => $idx,
            'order_id' => "1111111$idx",
            'invoice_id' => "INVOICE$idx",
            'order_timestamp' => strtotime("$idx may 2010"),
            'expiry_timestamp' => strtotime("$idx may 2010 12:30:00"),
            'expiry_hours' => $idx,
            //'total_amount' => '$ ' . $idx * 100 . '.00',
            'total_amount_raw' => $idx * 1000,
            'item_total_amount_raw' => $idx * 500,
            'status' => ($idx%2) ? 'Processing' : 'Pending',
            'merchant_remarks' => "This is the remarks.\nThis is the second line.",
            'message' => ($idx%2) ? "This is message line 1.\nThis is message line 2.\nThis is message line 3." : '',
            'payment_notif_message' => "This is payment message line 1.\nThis is payment message line 2.\nThis is payment message line 3.",
            'prefered_delivery_timestamp' => strtotime("$idx june 2010"),
            'coupon_code' => ($idx%2) ? "ABCDE" : '',
            'n_points_earned' => ($idx%2) ? $idx*10 : '',
            'n_points_used' => $idx+1,
            'items' => array(
                'Item 1 (Size M)',
                'Item 2 (Size L Color Green)',
                'Item 3',
            ),
            'invoice_link' => ($idx%2) ? "http://invoice$idx" : '',
            'currency' => 'SGD',
            'detailed_items' => array(
                array(
                    'title' => 'Product 1 Very Very Long Title',
                    'quantity' => 1,
                    'price' => 1000,
                    'options' => array(
                        'size' => 'L',
                        'color' => 'Blue',
                    ),
                    'image' => '/images/test/product_a_tn.jpg',
                ),
                array(
                    'product_code' => 'PROD1',
                    'title' => 'Product 2',
                    'quantity' => 2,
                    'price' => 2000,
                    'options' => array(),
                    'image' => '/images/test/product_a_tn.jpg',
                ),
                array(
                    'title' => 'Product 3 Very Very Long Title',
                    'quantity' => 1,
                    'price' => 500,
                ),
            ),
            'shipping' => ($idx%2) ? 10 : 0,
            'discount' => ($idx%2) ? -2.5 : 0,
            'tax' => ($idx%2) ? 1.555 : 0,
            'down_payment' => !($idx%3) ? 2000 : 0,
            'shipping_info' => 'Local Registered Post',
            'backorder_shipping_info' => ($idx%2) ? 'AM Delivery' : '',
            'event_name' => 'Some Event',
            'event_venue' => 'Some Venue',
            'event_timestamp' => strtotime('13 January 2010 13:25:51'),
            'payment_method' => "Payment Method $idx",
            'payment_instruction' => "<ul><li>ABC Bank 001-001-001</li><li>XYZ Bank 002-002-002</li></ul>",
            'attribute1' => 'Some Attribute 1',
            'attribute2' => 'Some Attribute 2',
            'attribute3' => 'Some Attribute 3',
            'attribute4' => 'Some Attribute 4',
            'attribute5' => 'Some Attribute 5',
            'billing_info' => array(
                'name' => 'John Smith',
                'address' => '10 Singapore Street #12-123',
                'city' => 'Singapore City',
                'postal_code' => '543210',
                'state' => 'Singapore State',
                'country' => 'Singapore Country',
                'phone' => '12345678',
                'email' => 'john@smith.com',
                'salutation' => 'Dr',
                'id_number' => 'S7654321T',
                'nationality' => 'SG',
                'phone2' => '22222222',
                'dob_timestamp' => strtotime('8 December 1983'),
            ),
            'delivery_info' => array(
                'name' => 'Alice Bob',
                'address' => 'Jl. Semangka Barat II/41',
                'city' => 'Surabaya',
                'postal_code' => '98765',
                'state' => 'Jawa Timur',
                'country' => 'Indonesia',
                'phone' => '23456789',
                'email' => 'alice@bob.com',
            ),
        );
        if ($idx == 3) {
            $order['items'] = array();
        }
        $order += Helper_ViewTest::getAdditionalTestFields($controller, 'orders', $idx);
        return $order;
    }

    static function _getViewTestValue($field, $idx)
    {
        $viewTestValue = $field['label'] . ' ' . $idx;
        if (isset($field['view_test_generator'])) {
            if ($field['view_test_generator'] instanceof Closure) {
                $gen = $field['view_test_generator'];
                $viewTestValue = $gen($idx);
            } else {
                $viewTestValue = $field['view_test_generator'];
            }
        }
        return $viewTestValue;
    }

    static function getAdditionalTestFields($controller, $configKey, $idx)
    {
        if (!$controller) {
            return array();
        }
        $addFields = array();
        if ($gen = $controller->getKrcoConfigValue($configKey, 'obj_view_test_generator')) {
            $addFields = $gen($idx);
        }
        $formFields = $controller->getKrcoConfigValue($configKey, 'form_fields');
        if ($formFields) {
            foreach ($formFields as $field) {
                if (isset($field['pass_to_view']) && $field['pass_to_view']) {
                    $fieldKey = $field['pass_to_view'];
                    $viewTestValue = self::_getViewTestValue($field, $idx);
                    $addFields[$fieldKey] = $viewTestValue;
                }
            }
        }
        return $addFields;
    }

    static function getTestEmailSignature()
    {
        $sig = <<<EOD
<p>
Cheers,<br />
The Company
</p>

EOD;
        return $sig;
    }

    static function getTestOtherThings($controller, $n=7)
    {
        return $controller->getTestObjs('OtherThing', 1, $n);
    }

    static function getTestOtherThing($controller, $idx)
    {
        return array(
            'code' => "$controller->now $idx",
        );
    }

    static function getTestGeneralObject7s($controller, $n=7)
    {
        if (!isset($n)) {
            $n = 7;
        }
        return $controller->getTestObjs('GeneralObject7', 1, $n);
    }

    static function getTestGeneralObject7($controller, $idx)
    {
        $arr = array(
            'title' => "General Object7 $idx",
            'description' => Helper_ViewTest::getLoremIpsumP(2) . "\n" . Helper_ViewTest::getLoremIpsumP(1),
            'label' => "Label " . (($idx-1) % 7 + 1),
            'images' => array(
                $controller->resource_url . "/images/test/general_object7_${idx}a.jpg",
                $controller->resource_url . "/images/test/general_object7_${idx}b.jpg",
            ),
        );
        $arr = array_merge($arr, Helper_ViewTest::getAdditionalTestFields($controller, 'general_objects7', $idx));
        return $arr;
    }

    static function getTestGeneralObject2s($controller, $n=7)
    {
        if (!isset($n)) {
            $n = 7;
        }
        return $controller->getTestObjs('GeneralObject2', 1, $n);
    }

    static function getTestGeneralObject2($controller, $idx)
    {
        $arr = array(
            'fid' => "general-object2-$idx",
            'title' => "General Object2 $idx",
            'label' => "Category " . (($idx-1)%3+1),
            'description' => Helper_ViewTest::getLoremIpsumP(2) . "\n" . Helper_ViewTest::getLoremIpsumP(1),
            'short_description' => Helper_ViewTest::getLoremIpsumP(1) . "\n" . Helper_ViewTest::getLoremIpsumP(1),
            'link' => "http://general_object$idx",
            'images' => array(
                $controller->resource_url . "/images/test/general_object2_${idx}a.jpg",
                $controller->resource_url . "/images/test/general_object2_${idx}b.jpg",
            ),
        );
        $arr = array_merge($arr, Helper_ViewTest::getAdditionalTestFields($controller, 'general_objects2', $idx));
        return $arr;
    }

    static function getTestGeneralObject1s($controller, $n=7)
    {
        if (!isset($n)) {
            $n = 7;
        }
        return $controller->getTestObjs('GeneralObject1', 1, $n);
    }

    static function getTestGeneralObject1($controller, $idx)
    {
        $arr = array(
            'fid' => "general-object-$idx",
            'title' => "General Object $idx",
            'label' => "Category " . (($idx-1)%3+1),
            'description' => Helper_ViewTest::getLoremIpsumP(2) . "\n" . Helper_ViewTest::getLoremIpsumP(1),
            'short_description' => Helper_ViewTest::getLoremIpsumP(1) . "\n" . Helper_ViewTest::getLoremIpsumP(1),
            'link' => "http://general_object$idx",
            'images' => ($idx%2) ? array(
                $controller->resource_url . "/images/test/general_object_a$idx.jpg",
                $controller->resource_url . '/images/test/general_object_b.jpg',
                $controller->resource_url . '/images/test/general_object_c.jpg',
                $controller->resource_url . '/images/test/general_object_d.jpg',
            ) : array(),
        );
        $arr += Helper_ViewTest::getAdditionalTestFields($controller, 'general_objects', $idx);
        return $arr;
    }

    static function getTestGeneralCategories($controller, $n=7)
    {
        if (!isset($n)) {
            $n = 7;
        }
        return $controller->getTestObjs('GeneralCategory', 1, $n);
    }

    static function getTestGeneralCategory($controller, $idx)
    {
        $arr = array(
            'id' => $idx,
            'parent_id' => ($idx%2) ? $idx*10 : 0, 
            'title' => "General Category $idx",
            'description' => Helper_ViewTest::getLoremIpsumP(2) . "\n" . Helper_ViewTest::getLoremIpsumP(1),
            'short_description' => Helper_ViewTest::getLoremIpsumP(1) . "\n" . Helper_ViewTest::getLoremIpsumP(1),
            'images' => array(
                $controller->resource_url . "/images/test/general_category_${idx}a.jpg",
                $controller->resource_url . "/images/test/general_category_${idx}b.jpg",
            ),
            'link' => "http://general_category$idx",
        );
        $arr = array_merge($arr, Helper_ViewTest::getAdditionalTestFields($controller, 'general_categories', $idx));
        return $arr;
    }

    static function getTestBankAccount($controller, $idx)
    {
        return array(
            'title' => "Bank Account $idx",
            'display' => "Bank Account $idx 000-000-00$idx",
        );
    }

    static function getTestInvitation($controller, $idx)
    {
        $arr = array(
            'email' => "email$idx@domain.com",
            'referer_email' => "referer$idx@domain.com",
        );
        return $arr;
    }

    static function getTestCart($controller)
    {
        $cart = array(
            'currency_symbol' => 'RM',
            'items' => $controller->getTestCartItems(),
            'shipping' => 0,
            'shipping_discount' => 0,
            'link' => 'http://localhost/debug.php',
            'checkout_link' => 'http://cartcheckout',
            'code' => 'cart-code',
            'backorder_shipping_method' => 'Registered Shipping',
            'shipping_method' => 'Normal Shipping',
            'backorder_shipping_destination' => 'SG',
            'shipping_destination' => 'ID',
            'shipping_country' => 'MY',
            'shipping_city' => 'Surabaya',
            'coupon_code' => 'ABC',
            'discounts' => array(),
            'total_items' => 2,
            'total_weight' => 2750,
            'npoints' => 4,
            'npoints_earned' => 40,
            'grand_total' => 1300.5,
            'item_total' => 1200.5,
            'is_checkout_enabled' => TRUE,
            'checkout_disabled_reason' => '',
            'delivery_timestamp' => time() + (10*24*3600),
            'tax' => 0,
            'point_rule' => array(
                'point_per_dollar' => 1.4567,
                'dollar_per_point' => 0.2345,
            ),
        );
        return $cart;
    }

    static function getTestCoupon($controller, $idx)
    {
        $arr = array(
            'coupon_type' => 'product',
            'coupon_code' => 'COUPON7',
            'discount_percentage' => $idx*10,
            'discount_amount' => $idx,
            'expiry_date' => "$idx December 2021"
        );
        return $arr;
    }

    static function getTestSubscription($controller, $idx)
    {
        $statuses = array(
            NULL,
            'active',
            'expiring',
            'expired',
        );
        $subscription = array(
            'first_name' => 'John',
            'last_name' => 'Smith',
            'subscription_id' => "SUB000$idx",
            'product_name' => "Product $idx",
            'start_timestamp' => strtotime("$idx june 2010"),
            'end_timestamp' => strtotime("$idx june 2011"),
            'next_end_timestamp' => strtotime("$idx june 2012"),
            'expiry_status' => $statuses[($idx-1)%(count($statuses))],
            'is_subscription_archived' => ($idx===5 || $idx === 6) ? TRUE : FALSE,
            'hash' => "hash$idx",
        );
        $subscription = Helper_ViewTest::getAdditionalTestFields($controller, 'subscribe', $idx) + $subscription;
        return $subscription;
    }

    static function generalGetTest($controller, $getMethod, $idx)
    {
        if (method_exists('Helper_ViewTest', $getMethod)) {
            return Helper_ViewTest::$getMethod($controller, 7);
        } else if (isset($controller->krco_config['obj_get_test'][$getMethod])) {
            $method = $controller->krco_config['obj_get_test'][$getMethod];
            return $method($controller, $idx);
        }
    }
}
