# Koalaful-state_Iteration3
**Developer:** MP38 - EAST  
**Wordpress Version:** WordPress 5.5.1  
**Brief Description:** This repository is for our team's IE project Iteration 3.  

## Installation  
In this part, we take AWS EC2 instance as an example and teach you how to install and run this WordPress on your AWS EC2 instance.  
### Step 1: Installing the Apache Web Server  
To run WordPress, you need to run a web server on your EC2 instance. The open source [Apache web server](https://httpd.apache.org/) is the most popular web server used with WordPress.  
To install Apache on your EC2 instance, run the following command in your terminal:  
```
sudo yum install -y httpd
```
You should see some terminal output of the necessary packages being installed.  
To start the Apache web server, run the following command in your terminal:  
```
sudo service httpd start
```
You can see that your Apache web server is working and that your security groups are configured correctly by visiting the public DNS of your EC2 instance in your browser.  
### Step 2: Download and Configure WordPress  
Before installation, you should install Git on your EC2 instance:  
```
sudo apt-get install git
```
Clone this repository from Github  
```
git clone git://github.com/zshi0014/Koalaful-state_Iteration2.git
```
Change into the wordpress directory and create a copy of the default config file using the following commands:  
```
cd Koalaful-state_Iteration2  
```
Then, open the **wp-config.php** file using the nano editor by running the following command.  
```  
nano wp-config.php  
```
You need to edit two areas of configuration.  
First, edit the database configuration by changing the following lines:  
```
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'database_name_here' );

/** MySQL database username */
define( 'DB_USER', 'username_here' );

/** MySQL database password */
define( 'DB_PASSWORD', 'password_here' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );
```
The values should be:  
* **DB_NAME:** Your database's name.  
* **DB_USER:** The name of the user you created in the database.  
* **DB_PASSWORD:** The password for the user.  
* **DB_HOST:** The hostname of the database.  

The second configuration section you need to configure is **the Authentication Unique Keys and Salts**. It looks as follows in the configuration file:  
```
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );
```
Go to this link to generate values for this configuration section. You can replace the entire content in that section with the content from the [link](https://api.wordpress.org/secret-key/1.1/salt/).  
You can save and exit from nano by entering CTRL + O followed by CTRL + X.  
With the configuration updated, you are almost ready to deploy your WordPress site. In the next step, you will make your WordPress site live.  
### Step 3: Deploying WordPress  
In this step, you will make your Apache web server handle requests for WordPress.  
First, install the application dependencies you need for WordPress. In your terminal, run the following command.  
```
sudo amazon-linux-extras install -y lamp-mariadb10.2-php7.2 php7.2
```
Second, change to the proper directory by running the following command:  
```
cd /home/ec2-user
```
hen, copy your WordPress application files into the **/var/www/html** directory used by Apache.  
```
sudo cp -r Koalaful-state_Iteration2/* /var/www/html/
```
Finally, restart the Apache web server to pick up the changes.  
```
sudo service httpd restart
```
You should see the WordPress welcome page and the five-minute installation process.  

## Some Main Plugins We Used On Our WorPress Website  
| Plugin Name       | Brief Description                          | Version |
| ----------------- |--------------------------------------------| --------|
|All-in-One WP Migration|Migration tool for all your blog data. Import or Export your blog content with a single click.|7.27|
|Elementor|The most advanced frontend drag & drop page builder. Create high-end, pixel perfect websites at record speeds. Any theme, any page, any design.|3.0.4|
|Insert PHP Code Snippet|Insert and run PHP code in your pages and posts easily using shortcodes. This plugin lets you create a shortcode corresponding to any random PHP code and use the same in your posts, pages or widgets.|1.3.1|
|Password Protected|A very simple way to quickly password protect your WordPress site with a single password. Please note: This plugin does not restrict access to uploaded files and images and does not work with some caching setups.|2.3|
