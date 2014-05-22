Magento SMTP Pro Extension
by Ashley Schroder (aschroder.com)

- Free and Opensource email extension for Magento
- Easily send Magento transactional emails via Google Apps, Gmail, Amazon SES or your own SMTP server.
- Test your conifguration from the Magento admin
- View a log of all emails
- Improve deliverability with an external SMTP server

Contributors
nl_NL translations thanks to [Melvyn Sopacua](http://www.supportdesk.nu/)
es_ES translations thanks to [Jhoon Saravia](http://twitter.com/jsaravia)


FAQ

Q: It's not working
A: Check for extension conflicts, and check that your host allows outbound SMTP traffic

Q: Does it work with the Mailchimp extension
A: yes, see: http://www.aschroder.com/2011/09/using-smtp-pro-and-ebizmarts-mailchimp-extension-in-magento/

Q: How do I install it manually
A: See: http://www.aschroder.com/2010/05/installing-a-magento-extension-manually-via-ftp-or-ssh/ or use modman.

Q: Self test is failing with "Exception message was: Unable to connect via TLS"
A: Check that you have OpenSSL installed for your PHP environment.

Q: Self test is failing with messages like: "can not open connection to the host, on port 587" or "Connection timed out".
A: Check that you have the SMTP server host and port correct, if you do - then check with your webhost, many block SMTP connections due to spam. If that's the case, there are plenty of expert Magento hosts on display at magespeedtest.com.

Q: Self test is failing with "Exception message was: 5.7.1 Username and Password not accepted. Learn more at 5.7.1..."
A: It's actually good advice to learn more here:  http://support.google.com/mail/bin/answer.py?answer=14257. But two things to check: 
1) that you are really 110% sure you have the right username and password (test it on gmail.com)
2) If that does work, then Google may have blocked your server IP due to too many wrong passwords. You need to log in to gmail.com _from_ that IP - in order to answer the captcha and allow the IP through again. There's a few ways to do that - SOCKS proxy, X forward a browser, use Lynx.
