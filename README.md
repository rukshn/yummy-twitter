![Yummy Twitter](http://www.officialpsds.com/images/thumbs/Splatter-Twitter-Logo-psd49400.png)
Yummy Twitter
=============

Yummy Twitter is a simple PHP oAuth tool that you can use to communicate with the Twitter API 1.1.

I just finished writing it today couple of hours, currently it handles the oAuth requests and returns an oauth_token and oauth_token_secret which you can use to make API calls to Twitter such as for posting tweets, reading tweets etc.

**Before you get started,**

 1. Before you get started make sure you register a new application on twitter by going to [Twitter app](https://apps.twitter.com/), and get the consumer key and consumer key secret of your app.
 2. Open the config.php file in Yummy Twitter and add the consumer key and consumer key secret to config.php file. And set the CALLBACK_URL of the config.php to the path of the Yummy Twitter callback.php is located.
 3. And set the path of your application URL as the APP_URL in the config.php.

After setting these variables you are good to go.

**How to make API calls**

Making api calls is easy using Yummy Twitter. Now you can make 'GET' or 'POST' requests. Use the **make_request** function to make API requests.

The syntax is make_request(method, parameters, url)

Method can be 'get' or 'post'.
Parameters are the necessary parameters for the request passed as an array.
URL is the part of the URL listed as in the documentation. For example if you are posting a tweet to twitter the URL should be 'statuses/update'

_This example shows how to post a tweet using Yummy Twitter_

```
  $status = "Posted using Yummy Twitter";
  $parameters = array('status' => $status);
  $request = make_request('POST', $parameters , 'statuses/update');
  print_r($request);

```

Same can be done to make a GET request.
