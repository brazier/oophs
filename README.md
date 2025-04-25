# OPEN OpenPeak Heartbeat Server
A simple implementation to talk to OpenPeak devices. The code might be messy, but it works. 

Using:
* [Bootstrap](https://getbootstrap.com/) ([License](https://github.com/twbs/bootstrap/blob/main/LICENSE))
* [PrismJS](https://prismjs.com/) ([License](https://github.com/PrismJS/prism/blob/master/LICENSE))
* [Font Awsome](https://fontawesome.com/) ([License](https://fontawesome.com/license/free))

>[!CAUTION]
>There is no authentication and anyone with access to the server can run code on any of the devices thats pointed to the server. Should not be left accessible and used only internally.

> [!TIP]
> It is adviseable to keep the DNS record pointing to your server after you are done, since there is nothing stopping others from running code on your device if they get access to the domain.
## How to use:
1. Setup a local DNS(e.g. PiHole) and point services.openpeak.net to your servers local IP-address, and add your DNS IP-adsress to you DHCP server.
2. Install Apache with mod_rewrite and mod_ssl.
   > SSL might not be needed as others have reported uisng standard HTTP on port 443 instead, and others have been able to use a standard SSL config
   > There exists different devices running different software from different vendors. This is what was needed on mine running software from _"Telio"_
   ```
   sudo apt install apache2 libapache2-mod-php8.2 openssl
   sudo s2enmod ssl
   sudo a2enmod rewrite
   ```
3. Generate a self signed cert and key:
   ```
   sudo mkdir -p /etc/ssl/localcerts
   sudo openssl req -new -x509 -days 365 -noenc -out /etc/ssl/localcerts/apache.pem -keyout /etc/ssl/localcerts/apache.key
   sudo chmod 600 /etc/ssl/localcerts/apache*
   ```
4. Edit sites-enabled/default-ssl.conf
   ```
   SSLCertificateFile      /etc/ssl/localcerts/apache.pem
   SSLCertificateKeyFile   /etc/ssl/localcerts/apache.key
   ```
5. Change ssl.conf to make it accept old ciphers/protocols ([source](https://ssl-config.mozilla.org/#server=apache&version=2.4.60&config=old&openssl=3.4.0&hsts=false&ocsp=false&guideline=5.7))
   > [!CAUTION]
   > This is not adviseable and could pose a security risk on a production server
    ```
    SSLProtocol             -all +TLSv1 +TLSv1.1 +TLSv1.2 +TLSv1.3
    SSLOpenSSLConfCmd       Curves X25519:prime256v1:secp384r1
    SSLCipherSuite          @SECLEVEL=0:ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384:DHE-RSA-CHACHA20-POLY1305:ECDHE-ECDSA-AES128-SHA256:ECDHE-RSA-AES128-SHA256:ECDHE-ECDSA-AES128-SHA:ECDHE-RSA-AES128-SHA:ECDHE-ECDSA-AES256-SHA384:ECDHE-RSA-AES256-SHA384:ECDHE-ECDSA-AES256-SHA:ECDHE-RSA-AES256-SHA:DHE-RSA-AES128-SHA256:DHE-RSA-AES256-SHA256:AES128-GCM-SHA256:AES256-GCM-SHA384:AES128-SHA256:AES256-SHA256:AES128-SHA:AES256-SHA:DES-CBC3-SHA
    SSLHonorCipherOrder     on
    SSLSessionTickets       off
    ```
6. Download files to document root of your server and make a folder named 'devicefiles' and make sure the server has write and read permissions to that folder.
7. Go to services.openpeak.net and enter the mac address of the device:
   
   ![bilde](https://github.com/user-attachments/assets/5bf26504-e6ad-43ab-9de8-db15fe64a1f4)
   
   if you dont know you device mac you can check apache logs or look for a `[MAC-ADDRESS].heartbeat` file in the `devicefiles/` folder.
   ```
   10.0.0.xx - - [24/Apr/2025:22:31:28 +0000] "GET /dms/device/heartbeat?mac=DE:AD:CA:FE:BA:BE HTTP/1.1" 200 1410 "-" "OpenPeak DMS Client/v0.2"
   ```
9. Send one of the xml files to the device, and wait for a reply(auto update every 5 second). The device will only run it once
    ![bilde](https://github.com/user-attachments/assets/41d09249-a01d-40e7-afa6-5f7409493a2e)

## How it works
* Whenever a user accesses `services.openpeak.net?mac=[MAC-ADDRESS]`
  1. Every 5 seconds, updates the "heartbeat" in the top left corner from `[MAC-ADDRESS].heartbeat` and updates the "Response" field from `[MAC-ADDRESS].reply`
  2. When "Send" is clicked, writes a number 1-3 to `[MAC-ADDRESS].run`

     Example `[MAC-ADDRESS].run` file when "Send command" is choosen:
     ```
     3
     cd /some/dir
     ```
* Whenever a device accesses `services.openpeak.net/dms/devices/heartbeat?mac=[MAC-ADDRESS]` (_ie. heartbeat.php_):
  1. A corresponding `[MAC-ADDRESS].heartbeat` file is made/updated with the current UNIX timestamp inside `devicefiles/`
  2. If a `[MAC-ADDRESS].run` exists, output corresponding XML for the device to process. Then delete `[MAC-ADDRESS].run ` 
