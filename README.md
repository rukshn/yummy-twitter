![Yummy Twitter](http://www.officialpsds.com/images/thumbs/Splatter-Twitter-Logo-psd49400.png)
Yummy Twitter
=============

Yummy Twitter is a simple PHP oAuth tool that you can use to communicate with the Twitter API 1.1.

I just finished writing it today couple of hours, currently it handles the oAuth requests and returns an oauth_token and oauth_token_secret which you can use to make API calls to Twitter such as for posting tweets, reading tweets etc.

I will add the possibility to making other APIs calls easily via Yummy Twitter such as posting tweets, reading timeline etc, as I go along and will add comments to the code too.

**Before you get started,**

 1. Before you get started make sure you register a new application on twitter by going to [Twitter app](https://apps.twitter.com/), and get the consumer key and consumer key secret of your app.
 2. Open the config.php file in Yummy Twitter and add the consumer key and consumer key secret to config.php file. And set the CALLBACK_URL of the config.php to the path of the Yummy Twitter callback.php is located.
 3. And set the path of your application URL as the APP_URL in the config.php.

After setting these variables you are good to go.

![Oauth LOGO](http://farm3.static.flickr.com/2074/1529124811_aad3ecabf6_o.png)
 
