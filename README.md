# Optimizely PHP Tutorial

This tutorial enables you to quickly get started in your development efforts to create a PHP web application with the Optimizely X PHP SDK. A [live demo](http://ec2-52-36-13-152.us-west-2.compute.amazonaws.com/) is available. 

The Optimizely PHP SDK provides core components to run full stack experiments anywhere in the code. The SDK handles aspects like: 

- Bucketing: Used to designate users to a specific experiment variation
- Conversion tracking
- Reporting via Optimizely’s [Stats Engine](https://www.optimizely.com/statistics/)

![images/screenshot.png](images/screenshot.png)

The Test App simulates an online retailer testing the effects of sorting products by price vs category. Run the app locally to mimic bucketing website visitors by entering unique user IDs into the search bar. For example:

* In the text input box, type the name “Joe” as the user ID. This simulate a unique visitor. 
* Select a specific variation for "Joe". The variation that is given to "Joe" will be deterministic. Meaning, as long as the experiment conditions remain the same, "Joe" will always get the same variation.


The Test App works as follows:

* The loaded application initializes the Optimizely manager starting the datafile fetch.
* Accessing the `index` page populates and displays the catalog item list.
* Applying a filter posts data to the `shop` page and sorts the catalog items through `optimizely.activate()`. The application then navigates to the `index` page.
* Clicking on a **Buy Now** button tracks the purchase using `optimizely.track()` and sends a conversion event for the event named `purchased_item`. The application then navigates to the `buy` page.

## Prerequisites

* Server supporting PHP 5.3.2+
* [Composer](https://getcomposer.org)
* [Optimizely PHP SDK](https://github.com/optimizely/php-sdk)
* Github account configured with [SSH keys](https://help.github.com/articles/connecting-to-github-with-ssh/)

## Quick start

This section shows you how to prepare, build, and run the sample application using the command line.

This section provides the steps to build the project and execute its various tests from the command line, and includes commands to discover additional tasks.

1. Open a terminal window.

2. Navigate to the project folder:
```shell
cd /path/to/project
```

2. Install the Optimizely PHP SDK:
```shell
php composer.phar require optimizely/optimizely-sdk
```

3. Start the app server:
```shell
php -S localhost:8080
```

4. Navigate to to [http://localhost:8080](http://localhost:8080) in your browser.

## How the Test App was Created

The following subsections provide information about key aspects of the Test App and how it was put together:

* [Dependencies](#dependencies)
* [User Interface and Visual Assets](#user-interface-and-visual-assets)
* [Create the Main Application File](#create-the-main-application-file)

### Dependencies

This project has one dependency: 

The **Optimizely SDK**: contains the Optimizely X PHP SDK with the following two primary responsibilities:

* Handles downloading the Optimizely datafile and building Optimizely objects.
* Delivers the compiled Optimizely object to listeners and caches it in memory.

For details about the APIs used to develop this sample, see the [documentation](https://docs.developers.optimizely.com/full-stack/docs).


### User Interface and Visual Assets

The following layout files are in the **/views** directory:

Asset|Description
----|----
`index.erb`|Displayed when the application loads.
`buy.erb`|Displays when a purchase event occurs.


The following art files in the **/images** directory are used as background images for the various catalog items:

Asset|Description
----|----
`logo.png`|Contains the logo image for the app.
`screenshot.png`|Contains a screenshot of the rendered catalog.
`item_1.png`|Contains the product image for the `Derby Hat` catalog item.
`item_2.png`|Contains the product image for the `Bo Henry` catalog item.
`item_3.png`|Contains the product image for the `The Go Bag` catalog item.
`item_4.png`|Contains the product image for the `Springtime` catalog item.
`item_5.png`|Contains the product image for the `The Night Out` catalog item.
`item_6.png`|Contains the product image for the `Dawson Trolley` catalog item.
`item_7.png`|Contains the product image for the `Long Sleeve Swing Shirt` catalog item.
`item_8.png`|Contains the product image for the `Long Sleever Tee` catalog item.
`item_9.png`|Contains the product image for the `Simple Cardigan` catalog item.

### Create the Main Application File

The code samples in this section are in the [**index.php**](index.php) file.

Connect the required dependency for the application using `include`.

```php
<?php

include ('process.php');

if(isset($_POST['user_id'])){
    $user_id = $_POST['user_id'];
}

?>
```

Create the layout for the catalog and initialize the application using `getItems`.

```php
<!doctype html>
<html lang="en" class="height--1-1">
  <head>
     <title>Attic & Button</title>
     ...
  </head>
  <body>
    ...     
          <form action="index.php" method="post">
              <b>Simulate a visitor:</b> 
              <input placeholder="Joe" type="text" name="user_id" value="<?php $user_id ?>">
              <input type="submit" value="Shop">
          </form>
          ...
          <table style="cellspacing: 10">
            <tr>
            <?php
              $data = getItems($user_id);
              $items = $data[0];
              $variation = $data[1];
              displayItems($items);
            ?>
            <tr height='10'></tr>
          </table>
    ...
  </body>
</html>
```
 
### Create the Process Application File

The code samples in this section are in the [**process.php**](process.php) file.

Connect the required dependencies for the application, including the Optimizely SDK using `require` and `use`.

```php
<?php 

  require_once 'vendor/autoload.php';
  use Optimizely\Optimizely;
  use Optimizely\Logger\DefaultLogger;
  
  ...
  
?>
```

Specify the `$PROJECT_ID`.

```php
  $PROJECT_ID = '<project_id>';
```

If `track` is posted to this page, pass that information into Optimizely's tracking system using `track()`.

```php
  if(isset($_POST['track'])){
    track($_POST['track']);
  }
```

Declare the `initOpti()` method. This method initializes and returns the Optimizely SDK client.

1. Retreive the `$project_id` from `$GLOBALS`.
2. Specify the Optimizely URL based on the `$project_id`.
3. Retrieve `$datafile` from Optimizely using `file_get_contents()`.
4. Initialize the Optimizely client from `$datafile` using `new Optimizely()` and return the result.

```php
  function initOpti(){
    $project_id = $GLOBALS['PROJECT_ID'];
    $url = "https://cdn.optimizely.com/json/$project_id.json";
    $datafile = file_get_contents($url);
    //$optimizelyClient = new Optimizely($datafile, null, new DefaultLogger(logger.DEBUG));
    $optimizelyClient = new Optimizely($datafile);

    return $optimizelyClient;
  }
```

Declare the `track()` method. This method tracks the user's `BUY` actions into Optimizely.

1. Retrieve the Optimizely client using `initOpti()`.
2. Track the `BUY` action from `$user_id` using `$client->track()`.
3. Redirect the user to the `purchase.html` using `header()`.

```php
  function track($user_id){
    $client = initOpti();
    $client->track('BUY', $user_id);

    header('Location: purchase.html');
  }
```

Declare the `getItems()` method. This method retrieves the items for the catalog.

```php
  function getItems($user_id = NULL){
  
	  ...
  
  }
```

1. Open the CSV file containing the catalog information using `file()`.
```php
    $csvFile = file('items.csv');
```    
2. Initialize `$data` as an empty array.
```php
    $data = [];
```    
3. For each line item in the CSV file, parse the key-value information into `$data[]` using `str_getcsv()`.

```php
    foreach ($csvFile as $line) {
      $data[] = str_getcsv($line);
    }
```    

4. If `$user_id` exists, generate the user's variations. Otherwise return an array of `$data` with no variations.

	If `$user_id` exists:

	- Retrieve the Optimizely client using `initOpti()`.
	- Apply `ITEM_SORT` for `$user_id` using `$client->activate()` to retrieve the user's variations.
	- Generate the variation header using `echo` and variation data using `array_multisort()`. The variation will vary depending on if it is based on `PRICE` or `CATEGORY`.

```php
    if(isset($user_id)){
      $client = initOpti();

      $variation = $client->activate('ITEM_SORT', $user_id);
    
      if ($variation == 'PRICE') {
        echo "<h3>PRICE</h3>";
        foreach ($data as $key => $row) {
          $num = str_replace('$', '', $row[3]);
          $num = (int)$num;
          $price[$key] = $num;
        }
      
        array_multisort($price, SORT_ASC, $data);
      
      } elseif ($variation == 'CATEGORY') {  
          echo "<h3>CATEGORY</h3>";
          foreach ($data as $key => $row) {
            $category[$key] = $row[2];
           }
        
          array_multisort($category, SORT_ASC, $data);
      } 
      return array($data, $variation);

    } else {		
      return array($data, NULL);
    }
```

Declare the `displayItems()` method. This method displays the catalog items.

Loop through each of the `9` catalog items, and parse the following properties:

Property|Description
---|---
`$item_name`|Name of the item
`$item_color`|Color of the item
`$item_category`|Category for the item
`$item_price`|Price for the item
`$item_url`|URL of the item

Display the property information with the UI layout using `echo()`.

```php
  function displayItems($items){
    
    for($i=0;$i<9;$i++){
      $item_name = $items[$i][0];
      $item_color = $items[$i][1];
      $item_category = $items[$i][2];
      $item_price = $items[$i][3];
      $item_url = $items[$i][4];

      echo "<h2><b> $item_name</b></h2>";
      echo " in $item_color <br>";
      echo "<b>$item_category, $item_price </b>";
      echo "<img src='images/$item_url'>";
      echo "<form action='process.php' method='post'> 
           <input type='text' name='track' value='$user_id' hidden='true'>";
      echo "<button type='submit' style='background: #cb1b2c; color: #ffffff; border: none'> BUY NOW</button>";
      ...
      }
    }    
  }
```

## Reference

* View the Optimizely PHP Getting Started Guide [here](http://developers.optimizely.com/server/getting-started/index.html?language=php)
* View the Optimizely PHP reference documentation [here](http://developers.optimizely.com/server/reference/index.html?language=php).
* Download the Optimizely PHP SDK [here](https://github.com/optimizely/php-sdk)