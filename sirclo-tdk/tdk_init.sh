echo "Initiate SIRCLO TDK"
. read_ini.sh
#new_template name domain  
read_ini config.ini
trim() {
    local var=$@
    var="${var#"${var%%[![:space:]]*}"}"   # remove leading whitespace characters
    var="${var%"${var##*[![:space:]]}"}"   # remove trailing whitespace characters
    return -n "$var"
}

name="sirclo.tdk"
domain="sirclo.tdk"
ip="192.169.0.1"
temp="$ip"
os=`uname -s`

if [[ $os == "Linux" ]] || [[ "$OSTYPE" == "cygwin" ]]; then
        ip="127.0.0.1"
        temp="256.256.256.256"
fi


templates_dir=`pwd`

sudo="sudo"
etchostdir="/etc/hosts"
if [[ "$OSTYPE" == "cygwin" ]]; then
        etchostdir="/cygdrive/c/Windows/System32/Drivers/etc/hosts"
        sudo=" "
        templates_dir=$(cygpath -w `pwd`)
fi

words=$(egrep -wo "$domain|$temp" $etchostdir )
phpfpm="${INI__nginx_phpfpm}"
n=$(echo "$words" | wc -l)
if [[ "$words" == "" ]]; then
	n=0
fi
if (($n > 0)) ; then
  echo "ERROR!! Domain or IP already EXIST:\""$words"\" "
  exit
fi
mkdir -p "${INI__templates_dir}"

if [[ "${INI__webserver}" == "apache" ]]; then
	$sudo ./write_vhost.sh $etchostdir $templates_dir $domain ${INI__webserver} ${INI__apache_dir} $ip ${INI__xampp}
elif [[ "${INI__webserver}" == "nginx" ]]; then
	$sudo ./write_vhost.sh $etchostdir $templates_dir $domain ${INI__webserver} ${INI__nginx_dir} $ip ${INI__nginx_phpfpm}
else 
  echo "Invalid webserver, fix your config.ini"
  exit 1
fi
echo "Done"