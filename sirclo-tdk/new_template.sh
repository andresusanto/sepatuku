if [ "$#" -ne 3 ]
then
  echo "usage: $0 <template_name> <local_address> <local_ip_address>"
  exit 1
fi
echo "Adding new template"
. read_ini.sh
#new_template name domain  
read_ini config.ini
trim() {
    local var=$@
    var="${var#"${var%%[![:space:]]*}"}"   # remove leading whitespace characters
    var="${var%"${var##*[![:space:]]}"}"   # remove trailing whitespace characters
    return -n "$var"
}
name="$1"
domain="$2"
ip="$3"
templates_dir="${INI__templates_dir}/$name"
temp_dir="${INI__temp_dir}"

os=`uname -s`
temp="$ip"

if [[ $os == "Linux" ]] || [[ "$OSTYPE" == "cygwin" ]]; then
        ip="127.0.0.1"
fi

if [[ $ip == "127.0.0.1" ]]; then
        temp="256.256.256.256"
fi

sudo="sudo"
etchostdir="/etc/hosts"
if [[ "$OSTYPE" == "cygwin" ]]; then
        etchostdir="/cygdrive/c/Windows/System32/Drivers/etc/hosts"
        sudo=" "
fi
words=$(egrep -wo "$domain|$temp" $etchostdir )

n=$(echo "$words" | wc -l)
if [[ "$words" == "" ]]; then
  n=0
fi
if (($n > 0)) ; then
  echo "ERROR!! Domain or IP already EXIST:\""$words"\" "
  exit
fi



if [[ "${INI__webserver}" == "apache" ]]; then
	$sudo ./write_vhost.sh $etchostdir $templates_dir $domain ${INI__webserver} ${INI__apache_dir} $ip ${INI__xampp}
elif [[ "${INI__webserver}" == "nginx" ]]; then
	$sudo ./write_vhost.sh $etchostdir $templates_dir $domain ${INI__webserver} ${INI__nginx_dir} $ip ${INI__nginx_phpfpm}
fi

mkdir -p "$templates_dir"
cp -R template_skeleton/* "$templates_dir"
chmod -R 777 "$templates_dir"

touch "$templates_dir"/.sirclo-tdk
echo "smarty_dir = ${INI__smarty_dir}" > "$templates_dir"/.sirclo-tdk
echo "local_address = http://${domain}" >> "$templates_dir"/.sirclo-tdk
echo "temp_dir = ${INI__temporary_dir}" >> "$templates_dir"/.sirclo-tdk

touch "$templates_dir"/.htaccess
echo "RewriteEngine on" > "$templates_dir"/.htaccess
echo "RewriteCond \$1 !^(index\.php|resources|admin_resources|tmp_preview|favicon\.ico|images|css|js|fonts|README.html)"  >> "$templates_dir"/.htaccess
echo "RewriteRule ^(.*)$ index.php [L]" >> "$templates_dir"/.htaccess
echo "SetEnvIfNoCase ^Authorization$ \"(.+)\" PHP_AUTH_DIGEST_RAW=\$1" >> "$templates_dir"/.htaccess

echo "Done"