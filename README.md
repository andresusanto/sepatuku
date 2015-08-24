# sepatuku
Ben Ora Do Nyeker [![Deployment status from DeployBot](https://jeffhorus.deploybot.com/badge/77558060006890/40620.svg)](http://deploybot.com)

## Setting Environment (Apache)
1. Copy file `sirclo-tdk/config.ini.sample` menjadi `sirclo-tdk/config.ini`
  - Ubah lokasi smarty_dir, templates_dir, temporary_dir, and apache_dir sesuai dengan lokasi Anda
2. Copy file `sepatuku/.sirclo-tdk.sample` menjadi `sepatuku/.sirclo-tdk`
  - Ubah lokasi smarty_dir dan temp_dir sesuai dengan lokasi Anda
3. Ubah `config apache/conf/httpd.conf`
  - Cari kata `Include conf/extra/httpd-vhosts.conf` dan buang tanda `#` di depannya bila ada
4. Ubah config `apache/conf/extra/httpd-vhosts.conf`
  - Tambahkan kalimat berikut di paling bawah baris:
```<VirtualHost *:8000>
    DocumentRoot "D:\xampp\htdocs\sepatuku\templates\sepatuku"
    ServerName sepatuku.dev
    <Directory "D:\xampp\htdocs\sepatuku\templates\sepatuku">
        ServerSignature Off
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>```
  - Sesuaikan lokasi DocumentRoot dan Directory dengan lokasi sepatuku tersebut
5. Ubah config `Windows/System32/drivers/etc/hosts`
  - Buka file dengan Access Level Administrator
  - Tambahkan `127.0.0.1   sepatuku.dev` di paling bawah baris
