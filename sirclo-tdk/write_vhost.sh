#!/bin/sh
etchostdir=$1
templates_dir=$2
domain=$3
webserver=$4
webserver_dir=$5
ip=$6
os=`uname -s`
conf=""

if [ "$os" = "Darwin" ] && [ "$ip" != "127.0.0.1" ]; then
        touch "/Library/LaunchDaemons/com.$domain.plist"

echo "<plist version=\"1.0\">
  <dict>
    <key>Label</key>
      <string>com.$domain.plist</string>
    <key>ProgramArguments</key>
      <array>
        <string>/sbin/ifconfig</string>
        <string>lo0</string>
        <string>alias</string>
        <string>$ip</string>
        <string>netmask</string>
        <string>255.255.255.0</string>
      </array>
    <key>RunAtLoad</key>
      <true/>
  </dict>
</plist>" > "/Library/LaunchDaemons/com.$domain.plist"
launchctl load -w "/Library/LaunchDaemons/com.$domain.plist"
fi

echo "#Added by SIRCLO TDK" >> $etchostdir
echo "$ip $domain" >> $etchostdir
if [ "$webserver" = "apache" ]; then
    xampp=$7

    if [ "$xampp" = "1" ]; then
        conf="/etc"
    else
       conf=""
    fi

    if [[ "$OSTYPE" == "cygwin" ]]; then
        conf="/conf"
    fi
    echo "#Adding Virtual Host to your Apache"
    echo "
<VirtualHost ${domain}:80>
    DocumentRoot \"${templates_dir}\"
    ServerName ${domain}
    <Directory \"${templates_dir}\">
        ServerSignature Off
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
    " >> "${webserver_dir}$conf/extra/httpd-vhosts.conf"
elif [ "$webserver" = "nginx" ]; then
    phpfpm=$7
    echo "#Adding Virtual Host to your nginx"
    echo "server {
    listen       $ip:80;
    server_name  $domain;
    root    $templates_dir/;
    error_log ${webserver_dir}/logs/$domain.error.log;
    access_log  ${webserver_dir}/logs/$domain.access.log;

    ssl                  off;
    location ~ index\.php$ {
        include fastcgi_params;
        fastcgi_pass $phpfpm; 
        fastcgi_param  SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_index  index.php;
    } 
    location / {
        rewrite ^.*$ /index.php last;
    }
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|woff|ott|ttf|eot|html|htm|bmp|rtf|pdf|zip|tgz|gz|rar|bz2|csv|xls)$ {
        expires 30d;
        log_not_found off;
    } 
    if (!-e \$request_filename){
        rewrite ^(.*)$ /index.php?q=$1 last;
        break;
    }
}
    " >> "${webserver_dir}/sites-available/$domain"
    ln -sfv $webserver_dir/sites-available/$domain $webserver_dir/sites-enabled/$domain
    sudo nginx -s reload
fi
