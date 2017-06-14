# PHP SDK Demo App

This demo uses the PHP SDK, a part of Optimizely's Full Stack solution.


## Optimizely Full Stack Overview

Optimizely Full Stack allows developers to run experiments anywhere in their code! The Python SDK provides the core components to run a full stack experiment with Optimizely. It handles aspects like bucketing, which is used to designate users to a specific experiment variation, conversion tracking, and reporting via Optimizely’s [Stats Engine](https://www.optimizely.com/statistics/).  

* View the [Python Getting Started Guide](http://developers.optimizely.com/server/getting-started/index.html?language=php)

* View the reference [documentation](http://developers.optimizely.com/server/reference/index.html?language=php).

* Latest [Python SDK](https://github.com/optimizely/php-sdk)

## Demo App

This example app simulates an online retailer testing the effects of sorting products by price vs category.

Using the instructions below, you can run the app locally and mimic bucketing website visitors by entering unique user IDs into the search bar. For example, the user ID “Matt” would simulate a unique visitor and select a specific variation for that unique visitor. The variation that is given to a specific unique visitor, such as Matt, will be deterministic. This means as long as the experiment conditions remain the same, Matt will always get the same variation.
 
<img src="https://github.com/optimizely/python-sdk-demo-app/blob/master/images/screenshot.png" width="420" height="369px">
